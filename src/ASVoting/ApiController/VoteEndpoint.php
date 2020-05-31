<?php

declare(strict_types = 1);

namespace ASVoting\ApiController;

use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;
use SlimAuryn\Response\JsonResponse;
use VarMap\VarMap;
use ASVoting\Repo\VoteRecordingRepo\VoteRecordingRepo;
use Params\Exception\ValidationException;

class VoteEndpoint
{
    public function index()
    {
        return new JsonResponse(['status' => 'ok']);
    }

    // TIFF
    public function postVote(VarMap $varMap, VoteRecordingRepo $voteStorageRepo)
    {
        try {
            $vote = VoteToRecord::createFromVarMap($varMap);
            $voteStorageRepo->recordVote($vote);
        }
        catch (ValidationException $ve) {
        }
        catch (\Exception $e) {
            // e.g. duplicated
        }

        return new JsonResponse(['status' => 'ok']);
    }

    // TIFF
    public function deleteVote(VarMap $varMap, VoteRecordingRepo $voteStorageRepo)
    {
        try {
            $voteToDelete = VoteToDelete::createFromVarMap($varMap);
            $voteStorageRepo->deleteVote($voteToDelete);
        }
        catch (ValidationException $ve) {
        }
        catch (\Exception $e) {
            // e.g. duplicated
        }

        return new JsonResponse(['status' => 'ok']);
    }
}
