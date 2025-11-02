<?php

namespace App\Livewire\Club;

use App\Models\Licence;
use App\Models\LicenceDeadline;
use App\Models\ClubTeam;
use Livewire\Attributes\Locked;
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
     * Get active licences (licences with deadlines for user's clubs)
     */
    public function getActiveLicences()
    {
        $userId = auth()->id();

        // Get club IDs where user is a member
        $clubIds = ClubTeam::where('user_id', $userId)->pluck('club_id')->toArray();

        if (empty($clubIds)) {
            return Licence::whereRaw('1 = 0')->paginate(9);
        }

        // Get licence IDs that have deadlines for these clubs
        $licenceIds = LicenceDeadline::whereIn('club_id', $clubIds)
            ->pluck('licence_id')
            ->unique()
            ->toArray();

        $query = Licence::with(['season', 'league', 'licence_deadlines' => function($q) use ($clubIds) {
                $q->whereIn('club_id', $clubIds);
            }])
            ->whereIn('id', $licenceIds)
            ->where('is_active', true);

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('start_at', 'desc')->paginate(9);
    }

    /**
     * Get all active licences in the system
     */
    public function getAllLicences()
    {
        $query = Licence::with(['season', 'league'])
            ->where('is_active', true);

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%');
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

        if (!$userId) {
            return 0;
        }

        // Get club IDs where user is a member
        $clubIds = ClubTeam::where('user_id', $userId)->pluck('club_id')->toArray();

        if (empty($clubIds)) {
            return 0;
        }

        // Count unique licences that have deadlines for these clubs
        return LicenceDeadline::whereIn('club_id', $clubIds)
            ->distinct('licence_id')
            ->count('licence_id');
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
