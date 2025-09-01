<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class AdminComparisonController extends Controller
{
    public function index()
    {
        $images = Image::all();
        return view('admin.comparison.index', compact('images'));
    }

    public function compare(Request $request)
    {
        $image1 = Image::with('detections')->findOrFail($request->image1_id);
        $image2 = Image::with('detections')->findOrFail($request->image2_id);

        $results = [
            'matches' => [],
            'only_in_first' => [],
            'only_in_second' => [],
        ];

        foreach ($image1->detections as $det1) {
            $match = $image2->detections->first(function ($det2) use ($det1) {
                return $det1->target === $det2->target &&
                    abs($det1->x1 - $det2->x1) < 20 &&
                    abs($det1->y1 - $det2->y1) < 20;
            });

            if ($match) {
                $results['matches'][] = [$det1, $match];
            } else {
                $results['only_in_first'][] = $det1;
            }
        }

        foreach ($image2->detections as $det2) {
            $match = $image1->detections->first(function ($det1) use ($det2) {
                return $det1->target === $det2->target &&
                    abs($det1->x1 - $det2->x1) < 20 &&
                    abs($det1->y1 - $det2->y1) < 20;
            });

            if (!$match) {
                $results['only_in_second'][] = $det2;
            }
        }

        return view('admin.comparison.result', compact('image1', 'image2', 'results'));
    }
}