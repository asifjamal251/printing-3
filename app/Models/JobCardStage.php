<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCardStage extends Model
{
    protected $fillable = [
        'job_card_id',
        'name',
        'in_counter',
        'operator_id',
        'out_counter',
        'status_id',
        'order'
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    // Rule: out_counter must always be <= in_counter
    public function setOutCounter($value)
    {
        if ($value > $this->in_counter) {
            throw new \Exception("Out counter cannot exceed In counter for stage: {$this->name}");
        }
        $this->out_counter = $value;
    }


    // get by job card id and stage name
// $stageId = JobCardStage::where('job_card_id', $jobCardId)
//             ->where('name', 'PRINTING')
//             ->value('id');

// // get by job card id and order
// $stageId = JobCardStage::where('job_card_id', $jobCardId)
//             ->where('order', 2)
//             ->value('id');

// // get all stages with ids
// $stages = JobCardStage::where('job_card_id', $jobCardId)->pluck('id', 'name');


//     use App\Services\JobCardStageService;
// use App\Models\JobCardStage;

// $stage = JobCardStage::find($stageId);

// // Suppose printing completed 500 qty
// JobCardStageService::updateStageCounter($stage, 500);
}