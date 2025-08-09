<?php

namespace App\Http\Controllers\Admin;

use App\Models\Drone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class AdminDroneController extends Controller
{
    /**
     * Список дронов
     */
    public function index()
    {
        $drones = Drone::paginate(15);
        return view('admin.drones.index', compact('drones'));
    }

    /**
     * Форма добавления
     */
    public function create()
    {
        return view('admin.drones.create');
    }

    /**
     * Сохранение нового дрона
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['required', Rule::in(array_keys(Drone::statuses()))],
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'manufacture_date' => 'nullable|date',
            'firmware_version' => 'nullable|string|max:255',
        ]);

        Drone::create($validated);

        return redirect()->route('admin.drones.index')->with('success', 'Дрон создан');
    }

    /**
     * Форма редактирования
     */
    public function edit(Drone $drone)
    {
        // Список дронов не нужен, если нет связей с другими дронами
        return view('admin.drones.edit', compact('drone'));
    }


    /**
     * Обновление дрона
     */
    public function update(Request $request, Drone $drone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['required', Rule::in(array_keys(Drone::statuses()))],
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'manufacture_date' => 'nullable|date',
            'firmware_version' => 'nullable|string|max:255',
        ]);

        $drone->update($validated);

        return redirect()->route('admin.drones.index')->with('success', 'Дрон обновлён');
    }

    /**
     * Удаление дрона
     */
    public function destroy(Drone $drone)
    {
        $drone->delete();

        return redirect()->route('admin.drones.index')
            ->with('success', 'Дрон удалён!');
    }

    /**
     * Сдвиг дрона (просто пример)
     */
    /*  public function move(Drone $drone)
    {
        // Здесь можно сделать логику изменения координат
        $drone->latitude += 0.0001;
        $drone->longitude += 0.0001;
        $drone->save();

        return redirect()->route('admin.drones.index')
            ->with('success', 'Дрон сдвинут на карте!');
    } */
}
