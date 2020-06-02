import { h, render } from "preact";
import { MotionsPanel } from "./MotionsPanel";
// import {EventType, startEventProcessing} from "./events";


type ReactPanel = {
    class: string,
    component: Object
}

let panels: Array<ReactPanel> = [
    {
        class: 'motions_panel',
        component: MotionsPanel
    }
];

function setupPanel(element: HTMLOrSVGElement, component: Object)
{
    // if (panelElement === null) {
    //     // console.warn('controlPanel not present.');
    //     return;
    // }

    let params = {};

    // if (element.dataset.hasOwnProperty("panel_data_json") === true) {
    //     let json = element.dataset.panel_data_json;
    //     params = JSON.parse(json);
    // }

    // const react_type = <component {...params} />;
    // @ts-ignore: you not helping here.
    const react_type = h(component, params);

    render(
        react_type,
        // @ts-ignore: you not helping here.
        element
    );
}

function setupPanelType(panel: ReactPanel)
{
    var panelElements = document.getElementsByClassName(panel.class);
    let elements = [];

    for (var i = 0; i < panelElements.length; i++) {
        // take a static snapshot of the elements, to prevent
        // yo'dawging of panel creation.
        elements.push(panelElements.item(i));
    }

    for (var j in elements) {
        let element = elements[j];
        // TODO - type check this properly but JS is terrible
        // if(!(element as HTMLOrSVGElement)){
        //     continue;
        // }
        // @ts-ignore: you not helping here.
        setupPanel(element, panel.component);
    }
}

function setupPanelTypes(panelTypes: Array<ReactPanel>) {
    for (let panelType of panelTypes) {
        setupPanelType(panelType);
    }
}


(function(){
    setupPanelTypes(panels);
})();

console.log("ready");
console.log("bootstrap finished");
