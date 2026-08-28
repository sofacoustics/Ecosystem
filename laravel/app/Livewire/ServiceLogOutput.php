<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;

use App\Models\ServiceLog;

class ServiceLogOutput extends Component
{
  public ?ServiceLog $servicelog = null;

  public function render()
  {
    //$running = ServiceLog::find($this->serviceId)?->latest_value ??
    return view('livewire.service-log-output');
  }

  #[Computed]
  public function formattedValue(): string
  {
    // Use the null-safe operator (?->) to prevent errors on null
    if (! $this->servicelog) {
        return '0';
    }
    // Format to 2 decimal places with comma thousand separators
    return round($this->servicelog->refresh()->execution_time ?? 0);
  }
}
