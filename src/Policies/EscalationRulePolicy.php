<?php

namespace Escalated\Laravel\Policies;

use Escalated\Laravel\Models\EscalationRule;
use Illuminate\Support\Facades\Gate;

class EscalationRulePolicy
{
    public function viewAny($user): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function view($user, EscalationRule $escalationRule): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function create($user): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function update($user, EscalationRule $escalationRule): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function delete($user, EscalationRule $escalationRule): bool
    {
        return Gate::allows('escalated-admin', $user);
    }
}
