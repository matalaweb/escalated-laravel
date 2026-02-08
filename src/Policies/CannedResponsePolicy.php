<?php

namespace Escalated\Laravel\Policies;

use Escalated\Laravel\Models\CannedResponse;
use Illuminate\Support\Facades\Gate;

class CannedResponsePolicy
{
    public function viewAny($user): bool
    {
        return Gate::allows('escalated-agent', $user);
    }

    public function view($user, CannedResponse $cannedResponse): bool
    {
        return Gate::allows('escalated-agent', $user);
    }

    public function create($user): bool
    {
        return Gate::allows('escalated-agent', $user);
    }

    public function update($user, CannedResponse $cannedResponse): bool
    {
        if (! Gate::allows('escalated-agent', $user)) {
            return false;
        }

        return $cannedResponse->is_shared || $cannedResponse->created_by === $user->getKey();
    }

    public function delete($user, CannedResponse $cannedResponse): bool
    {
        if (! Gate::allows('escalated-agent', $user)) {
            return false;
        }

        return $cannedResponse->is_shared || $cannedResponse->created_by === $user->getKey();
    }
}
