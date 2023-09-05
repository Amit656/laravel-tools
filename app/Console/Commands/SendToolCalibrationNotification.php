<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Admin\ToolService;
use Carbon\Carbon;

class SendToolCalibrationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:calibration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send tool calibration date notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $toolService;
    public function __construct(ToolService $toolService)
    {
        parent::__construct();

        $this->toolService = $toolService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        if ($this->toolService->sendCalibrationDateNotification()) {
            $this->info('Tool calibration date notification sent successful!');
        }else{
            \Log::error('Tool calibration date notification not sent at ' . Carbon::now()->toDateTimeString());
        }
    }
}
