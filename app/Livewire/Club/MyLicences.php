<?php

namespace App\Livewire\Club;

use App\Models\ClubTeam;
use App\Models\Licence;
use App\Models\LicenceDeadline;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Мои лицензии')]
class MyLicences extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Tab state
    public $activeTab = 'active'; // 'active' or 'all'

    // Search
    public $search = '';

    public function mount()
    {
        // No authorization needed - all club users can view licences
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    /**
     * Get active licences (licences with deadlines for user's clubs where deadline hasn't ended)
     * Shows only licences that have licence_deadline where club_id IN (user's clubs from club_team)
     * and current date < deadline.end_at
     */
    public function getActiveLicences()
    {
        $userId = auth()->id();
        $now = now();

        // Get club IDs where user is a member (club_team.user_id = auth()->user()->id)
        $clubIds = ClubTeam::where('user_id', $userId)->pluck('club_id')->toArray();

        if (empty($clubIds)) {
            return Licence::whereRaw('1 = 0')->paginate(9);
        }

        // Get licence IDs that have active or upcoming deadlines for these clubs (now < end_at)
        $licenceIds = LicenceDeadline::whereIn('club_id', $clubIds)
            ->where('end_at', '>', $now)
            ->pluck('licence_id')
            ->unique()
            ->toArray();

        $query = Licence::with(['season', 'league', 'licence_deadlines' => function ($q) use ($clubIds, $now) {
            $q->whereIn('club_id', $clubIds)
                ->where('end_at', '>', $now);
        }])
            ->whereIn('id', $licenceIds)
            ->where('is_active', true);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title_ru', 'like', '%'.$this->search.'%')
                    ->orWhere('title_kk', 'like', '%'.$this->search.'%')
                    ->orWhere('title_en', 'like', '%'.$this->search.'%');
            });
        }

        return $query->orderBy('start_at', 'desc')->paginate(9);
    }

    /**
     * Get all licences (licences with expired deadlines for user's clubs)
     * Shows only licences that have licence_deadline where club_id IN (user's clubs from club_team)
     * and deadline.end_at <= current date (expired deadlines)
     */
    public function getAllLicences()
    {
        $userId = auth()->id();
        $now = now();

        // Get club IDs where user is a member (club_team.user_id = auth()->user()->id)
        $clubIds = ClubTeam::where('user_id', $userId)->pluck('club_id')->toArray();

        if (empty($clubIds)) {
            return Licence::whereRaw('1 = 0')->paginate(9);
        }

        // Get licence IDs that have expired deadlines for these clubs (end_at <= now)
        $licenceIds = LicenceDeadline::whereIn('club_id', $clubIds)
            ->where('end_at', '<=', $now)
            ->pluck('licence_id')
            ->unique()
            ->toArray();

        $query = Licence::with(['season', 'league', 'licence_deadlines' => function ($q) use ($clubIds) {
            $q->whereIn('club_id', $clubIds);
        }])
            ->whereIn('id', $licenceIds);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title_ru', 'like', '%'.$this->search.'%')
                    ->orWhere('title_kk', 'like', '%'.$this->search.'%')
                    ->orWhere('title_en', 'like', '%'.$this->search.'%');
            });
        }

        return $query->orderBy('start_at', 'desc')->paginate(9);
    }

    /**
     * Get count of active licences for badge
     */
    public static function getActiveLicencesCount()
    {
        $userId = auth()->id();

        if (! $userId) {
            return 0;
        }

        // Get club IDs where user is a member
        $clubIds = ClubTeam::where('user_id', $userId)->pluck('club_id')->toArray();

        if (empty($clubIds)) {
            return 0;
        }

        $now = now();

        // Count unique licences that have active deadlines for these clubs (end_at > now)
        return LicenceDeadline::whereIn('club_id', $clubIds)
            ->where('end_at', '>', $now)
            ->distinct('licence_id')
            ->count('licence_id');
    }

    /**
     * Check if deadline is currently open (between start_at and end_at)
     */
    public function isDeadlineOpen($deadline)
    {
        if (! $deadline) {
            return false;
        }

        $now = now();

        return $now->gte($deadline->start_at) && $now->lte($deadline->end_at);
    }

    /**
     * Check if deadline hasn't started yet
     */
    public function isDeadlineUpcoming($deadline)
    {
        if (! $deadline) {
            return false;
        }

        $now = now();

        return $now->lt($deadline->start_at);
    }

    /**
     * Get deadline status badge HTML
     */
    public function getDeadlineStatusBadge($deadline)
    {
        if (! $deadline) {
            return '';
        }

        $now = now();

        if ($now->lt($deadline->start_at)) {
            // Upcoming
            return [
                'type' => 'upcoming',
                'text' => 'Откроется '.$deadline->start_at->format('d.m.Y H:i'),
                'icon' => 'fa-clock',
                'classes' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            ];
        } elseif ($now->gte($deadline->start_at) && $now->lte($deadline->end_at)) {
            // Open
            return [
                'type' => 'open',
                'text' => 'Открыт для подачи',
                'icon' => 'fa-check-circle',
                'classes' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            ];
        } else {
            // Expired
            return [
                'type' => 'expired',
                'text' => 'Дедлайн истек',
                'icon' => 'fa-times-circle',
                'classes' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            ];
        }
    }

    public function render()
    {
        $licences = $this->activeTab === 'active'
            ? $this->getActiveLicences()
            : $this->getAllLicences();

        return view('livewire.club.my-licences', [
            'licences' => $licences,
        ])->layout(get_user_layout());
    }
}
