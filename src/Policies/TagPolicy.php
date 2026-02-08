<?php

namespace Escalated\Laravel\Policies;

use Escalated\Laravel\Models\Tag;
use Illuminate\Support\Facades\Gate;

class TagPolicy
{
    public function viewAny($user): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function view($user, Tag $tag): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function create($user): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function update($user, Tag $tag): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function delete($user, Tag $tag): bool
    {
        return Gate::allows('escalated-admin', $user);
    }
}
