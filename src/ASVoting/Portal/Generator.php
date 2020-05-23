<?php

declare(strict_types = 1);

namespace Portal;

use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ClassGenerator;

class Generator
{
    public function __construct()
    {
    }

    private function getConstructDestructMethods(EventStart $eventStart)
    {
        $methods = [];

        $destructBody = <<< DESTRUCT_BODY
if (\$this->was_ended === true) {
    return;
}

\$this->emitNotFinalised('%s');
DESTRUCT_BODY;


        $destructBody = sprintf(
            $destructBody,
            $eventStart->getName()
        );

        $constructBody = sprintf(
            '$this->emit([\'name\' => "%s"]);',
            $eventStart->getDescription()
        );

        $methods[] = new MethodGenerator(
            $name = '__construct',
            $parameters = [],
            $flags = MethodGenerator::FLAG_PUBLIC,
            $body = $constructBody,
            $docBlock = null
        );

        $methods[] = new MethodGenerator(
            $name = '__destruct',
            $parameters = [],
            $flags = MethodGenerator::FLAG_PUBLIC,
            $body = $destructBody,
            $docBlock = null
        );

        return $methods;
    }


    private function getNextEventMethods(EventStart $eventStart)
    {
        $methods = [];

        $endEventBodyTemplate = <<< DESTRUCT_BODY
\$this->emit(['name' => "%s"]);
\$this->close();

return new %s();
DESTRUCT_BODY;

        $finalEventBodyTemplate = <<< DESTRUCT_BODY
\$this->emit(['name' => "%s"]);
\$this->close();
DESTRUCT_BODY;


        foreach ($eventStart->getEndEvents() as $endEvent) {
//            $hasNextEvent = false;
//
//            if ($hasNextEvent === true) {
//                $endEventBody = sprintf(
//                    $endEventBodyTemplate,
//                    $endEvent->getDescription(),
//                    $eventStart->getEndEventClassname($endEvent)
//                );
//            }
//            else {
            $endEventBody = sprintf(
                $finalEventBodyTemplate,
                $endEvent->getDescription()
            );
//            }

            $methods[] = new MethodGenerator(
                $name = $endEvent->getName(),
                $parameters = [],
                $flags = MethodGenerator::FLAG_PUBLIC,
                $body = $endEventBody,
                $docBlock = null
            );
        }

        return $methods;
    }

    private function generateFirstStepClass(EventStart $eventStart)
    {
        $foo = new ClassGenerator();
//        $docblock = new DocBlockGenerator(
//            'Sample generated class',
//            'This is a class generated with Zend\Code\Generator.'
//        );

        $properties = [];
//        $properties[] = new PropertyGenerator('john');

        $methods = [];
        $methods = array_merge($methods, $this->getConstructDestructMethods($eventStart));
        $methods = array_merge($methods, $this->getNextEventMethods($eventStart));

        $foo->setName($eventStart->getName())
//            ->setDocblock($docblock)
            ->addProperties($properties)
            ->addMethods($methods)
            ->addTrait('Event');

        $code = $foo->generate();

        return $code;
    }

    private function generateFirstStepFunction(EventStart $event)
    {
        $comment = sprintf(
            "// %s",
            'log function for '. $event->getName()
        );
        $functionName = 'log_' . $event->getName() . '_start';
        $body =  sprintf(
            "return new Portal\%s();",
            $event->getName()
        );

        $codeBlock = <<< CODE_BLOCK

%s
function %s() {
    %s
}
CODE_BLOCK;

        $code = sprintf(
            $codeBlock,
            $comment,
            $functionName,
            $body
        );

        return $code;
    }

    private function generateFirstStepFunctions($events)
    {
        $code = '';

        foreach ($events as $event) {
            $code .= $this->generateFirstStepFunction($event);
            $code .= "\n";
        }

        return $code;
    }

    private function generateEndEventClass(EventStart $eventStart, EventEnd $endEvent)
    {
        $code = "";

        $endEventClass = new ClassGenerator();
//        $docblock = new DocBlockGenerator(
//            'Sample generated class',
//            'This is a class generated with Zend\Code\Generator.'
//        );

        $properties = [];
//        $properties[] = new PropertyGenerator('john');

        $methods = [];
        $endEventClass->setName($endEvent->getName())
//            ->setDocblock($docblock)
            ->addProperties($properties)
            ->addMethods($methods)
            ->addTrait('Event');

        return $endEventClass->generate();
    }

    private function generateEndEventClasses(EventStart $eventStart)
    {
        $code = "";

        foreach ($eventStart->getEndEvents() as $endEvent) {
            $code .= $this->generateEndEventClass($eventStart, $endEvent);
        }

        return $code;
    }

    /**
     * @param \Portal\EventStart[] $events
     * @return string
     */
    private function generateFirstStepClasses($events)
    {
        $code = '';

        foreach ($events as $event) {
            $code .= $this->generateFirstStepClass($event);
//            $code .= $this->generateEndEventClasses($event);
            $code .= "\n";
        }

        return $code;
    }

    /**
     * @param EventStart[] $events
     * @return string
     */
    public function generateFromEvents($events)
    {
        $code = "<?php\n\n//this file is autogenerated - edits will be lost.\n\n";


        $code .= "namespace Portal {\n\n";

        $code .= "use Portal\Event;\n\n";

        $code .= $this->generateFirstStepClasses($events);


        $code .= "\n}\n";

        $code .= "namespace {\n\n";
        $code .= $this->generateFirstStepFunctions($events);
        $code .= "\n}\n";

        return $code;
    }
}
