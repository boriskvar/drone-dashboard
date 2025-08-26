<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminImageController extends Controller
{
    /**
     * Список картинок
     */
    public function index()
    {
        $images = Image::latest()->paginate(20);

        return view('admin.images.index', [
            'images' => $images,
            'activeRoute' => 'admin.images.index',
        ]);
    }

    /**
     * Форма создания
     */
    public function create()
    {
        return view('admin.images.create', [
            'activeRoute' => 'admin.images.create',
        ]);
    }

    /**
     * Сохранение картинки
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'file'  => 'required|image|max:2048', // до 2МБ
        ]);

        // Загружаем файл
        $path = $request->file('file')->store('images', 'public');

        $image = Image::create([
            'title' => $validated['title'] ?? null,
            'path'  => $path,
        ]);

        return redirect()->route('admin.images.index')
            ->with('success', 'Картинка успешно загружена.');
    }

    /**
     * Просмотр картинки
     */
    public function show(Image $image)
    {
        return view('admin.images.show', [
            'image' => $image,
            'activeRoute' => 'admin.images.index',
        ]);
    }

    /**
     * Форма редактирования
     */
    public function edit(Image $image)
    {
        return view('admin.images.edit', [
            'image' => $image,
            'activeRoute' => 'admin.images.index',
        ]);
    }

    /**
     * Обновление картинки
     */
    public function update(Request $request, Image $image)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'file'  => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('file')) {
            // Удаляем старый файл
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            $path = $request->file('file')->store('images', 'public');
            $image->path = $path;
        }

        $image->title = $validated['title'] ?? $image->title;
        $image->save();

        return redirect()->route('admin.images.index')
            ->with('success', 'Картинка обновлена.');
    }

    /**
     * Удаление картинки
     */
    public function destroy(Image $image)
    {
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return redirect()->route('admin.images.index')
            ->with('success', 'Картинка удалена.');
    }
}
