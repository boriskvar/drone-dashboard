<div class="sticky-top pt-3">
  <h2 class="h4 mb-4">Админ-панель</h2>

  <nav class="nav flex-column">
    @php
      $adminMenuItems = [
          [
              'title' => 'Дроны',
              'url' => route('admin.drones.index'),
              'routes' => ['admin.drones.index', 'admin.drones.create', 'admin.drones.edit'],
          ],
          [
              'title' => 'Данные полёта',
              'url' => route('admin.flight_data.index'),
              'routes' => ['admin.flight_data.index', 'admin.flight_data.create', 'admin.flight_data.edit'],
          ],
          [
              'title' => 'Симуляция',
              'url' => route('admin.simulation.index'),
              'routes' => ['admin.simulation.index'],
          ],
          [
              'title' => 'Картинки',
              'url' => route('admin.images.index'),
              'routes' => ['admin.images.index', 'admin.images.create', 'admin.images.edit', 'admin.images.show'],
          ],
          [
              'title' => 'Обнаружения',
              'url' => route('admin.detections.index'),
              'routes' => [
                  'admin.detections.index',
                  'admin.detections.create',
                  'admin.detections.edit',
                  'admin.detections.show',
              ],
          ],
          [
              'title' => 'Цели',
              'url' => route('admin.targets.index'),
              'routes' => ['admin.targets.index', 'admin.targets.create', 'admin.targets.edit'],
          ],
      ];
    @endphp

    @foreach ($adminMenuItems as $item)
      @php
        // Если текущий маршрут в списке, делаем подсветку
        $isActive = in_array($activeRoute, $item['routes']);
      @endphp
      <a href="{{ $item['url'] }}"
         class="nav-link text-white mb-2 rounded {{ $isActive ? 'bg-primary' : 'hover-bg-gray-700' }}">
        {{ $item['title'] }}
      </a>
    @endforeach
  </nav>
</div>
