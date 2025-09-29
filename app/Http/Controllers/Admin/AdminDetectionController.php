<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\Detection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminDetectionController extends Controller
{
    /**
     * Список обнаружений
     */
    public function index()
    {
        $detections = Detection::with('image')->paginate(15);

        return view('admin.detections.index', [
            'detections' => $detections,
            'activeRoute' => 'admin.detections.index',
        ]);
    }

    /**
     * Форма создания
     */
    public function create()
    {
        $images = Image::all(); // чтобы выбрать картинку
        // dd($images->toArray());
        return view('admin.detections.create', compact('images'));
    }

    /**
     * Сохранение картинки
     */
    public function store(Request $request, Image $image)
    {
        $request->validate([
            'image_id' => 'required|exists:images,id',
            'target' => 'required|string|max:255',
            'x1' => 'required|numeric',
            'y1' => 'required|numeric',
            'x2' => 'required|numeric',
            'y2' => 'required|numeric',
        ]);

        $image = Image::findOrFail($request->image_id);

        $detection = new Detection([
            'target' => $request->target,
            'x1' => $request->x1,
            'y1' => $request->y1,
            'x2' => $request->x2,
            'y2' => $request->y2,
        ]);

        $image->detections()->save($detection);

        return redirect()->route('admin.images.show', $image)
            ->with('success', 'Обнаружение добавлено!');
    }






    /**
     * Просмотр обнаружения
     */
    public function show(Detection $detection)
    {
        // dd($detection->toArray());

        return view('admin.detections.show', [
            'detection' => $detection,
            'activeRoute' => 'admin.detections.index',
        ]);
    }

    /**
     * Форма редактирования
     */
    public function edit(Detection $detection)
    {
        $images = Image::all();

        return view('admin.detections.edit', [
            'detection' => $detection,
            'images' => $images,
            'activeRoute' => 'admin.detections.index',
        ]);
    }

    /**
     * Обновление обнаружения
     */
    public function update(Request $request, Detection $detection)
    {
        $data = $request->validate([
            'image_id' => 'required|exists:images,id',
            'target'  => 'required|string|max:255',
            'x1' => 'required|numeric',
            'y1' => 'required|numeric',
            'x2' => 'required|numeric',
            'y2' => 'required|numeric',
        ]);

        $detection->update($data);

        return redirect()->route('admin.detections.index')->with('success', 'Обнаружение обновлено');
    }

    /**
     * Удаление картинки
     */
    public function destroy(Detection $detection)
    {
        $detection->delete();
        return redirect()->route('admin.detections.index')->with('success', 'Обнаружение удалено');
    }
}