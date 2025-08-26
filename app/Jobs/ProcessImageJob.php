<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
// use Illuminate\Bus\Queueable;
use App\Models\Image;
use App\Models\Detection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $imageId;

    public function __construct($imageId)
    {
        $this->imageId = $imageId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $image = Image::find($this->imageId);
        if (!$image) return;

        Log::info("Processing image", ['id' => $image->id]);

        // 🔹 Заглушка для YOLO (пока просто случайные координаты)
        $x1 = rand(10, 100);
        $y1 = rand(10, 100);
        $x2 = $x1 + rand(50, 150);
        $y2 = $y1 + rand(50, 150);

        Detection::create([
            'image_id' => $image->id,
            'label'    => 'target',
            'x1'       => $x1,
            'y1'       => $y1,
            'x2'       => $x2,
            'y2'       => $y2,
        ]);
    }
}