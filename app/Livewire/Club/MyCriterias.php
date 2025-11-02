<?php

namespace App\Livewire\Club;

use App\Models\ApplicationCriterion;
use App\Models\ClubTeam;
use App\Models\CategoryDocument;
use Livewire\Component;
use Livewire\Attributes\Locked;

class MyCriterias extends Component
{
    /**
     * Get count of criteria checks for badge
     */
    public static function getCriteriaCheckCount()
    {
        $userId = auth()->id();

        if (!$userId) {
            return 0;
        }

        $user = auth()->user();

        // Get club IDs where user is a member
        $clubIds = ClubTeam::where('user_id', $userId)->pluck('club_id')->toArray();

        if (empty($clubIds)) {
            return 0;
        }

        // Get category IDs where user's role is allowed
        $categoryIds = CategoryDocument::whereJsonContains('roles', $user->role->value)
            ->pluck('id')
            ->toArray();

        if (empty($categoryIds)) {
            return 0;
        }

        // Get application IDs for user's clubs
        $applicationIds = \App\Models\Application::whereIn('club_id', $clubIds)
            ->pluck('id')
            ->toArray();

        if (empty($applicationIds)) {
            return 0;
        }

        // Count criteria that match all conditions
        return ApplicationCriterion::whereIn('application_id', $applicationIds)
            ->whereIn('category_id', $categoryIds)
            ->whereHas('application_status', function ($query) {
                $query->whereIn('value', [
                    'awaiting-documents',
                    'first-check-revision',
                    'industry-check-revision',
                    'control-check-revision',
                    'partially-approved'
                ]);
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.club.my-criterias')
            ->layout(get_user_layout());
    }
}
