<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Настройки</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Настройки WB
                            </h2>
                        </header>
                        <form method="post" action="{{ route('settings.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')
                            <div>
                                <x-input-label for="wb_token" value="Токен wb" />
                                <x-text-input id="wb_token" name="wb_token" type="password" class="mt-1 block w-full" :value="$setting->wb_token" />
                                <x-input-error :messages="$errors->updatePassword->get('wb_token')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="auto_feedback_answer" value="Автоответы" />
                                <x-text-input value="1" :checked="$setting->auto_feedback_answer" id="auto_feedback_answer" name="auto_feedback_answer" type="checkbox" />
                                <x-input-error :messages="$errors->updatePassword->get('auto_feedback_answer')" class="mt-2" />
                            </div>
                            <x-primary-button> Сохранить</x-primary-button>
                        </form>
                    </section>
                    <br><br>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-7xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Настройки Кластеров
                            </h2>
                        </header>
                        <form method="post" action="{{ route('settings.updateClusters') }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="cluster_name" value="Имя кластера" />
                                <x-text-input id="cluster_name" name="name" type="text" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label for="cluster_slug" value="Slug" />
                                <x-text-input id="cluster_slug" name="slug" type="text" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label for="cluster_sort" value="Сортировка" />
                                <x-text-input id="cluster_sort" name="sort" type="number" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label for="order_region_ids" value="Регионы" />
                                <div id="order_region_ids" class="mt-1 block w-full" style="height: 250px; overflow-y: scroll;">
                                    @foreach($regions as $region)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="order_region_ids[]" value="{{ $region->id }}" class="mr-2">
                                            {{ $region->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <x-input-label for="warehouse_ids" value="Склады" />
                                <div id="warehouse_ids" class="mt-1 block w-full" style="height: 250px; overflow-y: scroll;">
                                    @foreach($warehouses as $warehouse)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="warehouse_ids[]" value="{{ $warehouse->id }}" class="mr-2">
                                            {{ $warehouse->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <x-primary-button> Сохранить</x-primary-button>
                        </form>
                        <br>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Существующие кластеры</h3>
                            <ul>
                                @foreach($clusters as $cluster)
                                    <li class="mt-4">
                                        <div class="bg-gray-200 dark:bg-gray-700 p-4 rounded-lg">
                                            <div class="flex justify-between items-center cursor-pointer" onclick="toggleCluster({{ $cluster->id }})">
                                                <h4 class="text-lg font-semibold">{{ $cluster->name }}</h4>
                                                <span>&#9662;</span>
                                            </div>
                                            <div id="cluster-{{ $cluster->id }}" class="hidden mt-4">
                                                <form method="post" action="{{ route('settings.updateClusters') }}" class="mt-6 space-y-6">
                                                    @csrf
                                                    <input type="hidden" name="cluster_id" value="{{ $cluster->id }}">
                                                    <div>
                                                        <x-input-label for="cluster_name-{{ $cluster->id }}" value="Имя кластера" />
                                                        <x-text-input id="cluster_name-{{ $cluster->id }}" name="name" type="text" class="mt-1 block w-full" value="{{ $cluster->name }}" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="cluster_slug-{{ $cluster->id }}" value="Slug" />
                                                        <x-text-input id="cluster_slug-{{ $cluster->id }}" name="slug" type="text" class="mt-1 block w-full" value="{{ $cluster->slug }}" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="cluster_sort-{{ $cluster->id }}" value="Сортировка" />
                                                        <x-text-input id="cluster_sort-{{ $cluster->id }}" name="sort" type="number" class="mt-1 block w-full" value="{{ $cluster->sort }}" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="order_region_ids-{{ $cluster->id }}" value="Регионы" />
                                                        <div id="order_region_ids-{{ $cluster->id }}" class="mt-1 block w-full" style="height: 250px; overflow-y: scroll;">
                                                            @foreach($regions as $region)
                                                                <label class="flex items-center">
                                                                    <input type="checkbox" name="order_region_ids[]" value="{{ $region->id }}" {{ in_array($region->id, $cluster->regions->pluck('id')->toArray()) ? 'checked' : '' }} class="mr-2">
                                                                    {{ $region->name }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="warehouse_ids-{{ $cluster->id }}" value="Склады" />
                                                        <div id="warehouse_ids-{{ $cluster->id }}" class="mt-1 block w-full" style="height: 250px; overflow-y: scroll;">
                                                            @foreach($warehouses as $warehouse)
                                                                <label class="flex items-center">
                                                                    <input type="checkbox" name="warehouse_ids[]" value="{{ $warehouse->id }}" {{ in_array($warehouse->id, $cluster->warehouses->pluck('id')->toArray()) ? 'checked' : '' }} class="mr-2">
                                                                    {{ $warehouse->name }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <x-primary-button> Сохранить</x-primary-button>
                                                </form>
                                                <form method="post" action="{{ route('settings.removeRegionsAndWarehouses') }}" class="mt-6 space-y-6">
                                                    @csrf
                                                    <input type="hidden" name="cluster_id" value="{{ $cluster->id }}">
                                                    <div>
                                                        <x-input-label for="remove_order_region_ids-{{ $cluster->id }}" value="Удалить регионы" />
                                                        <div id="remove_order_region_ids-{{ $cluster->id }}" class="mt-1 block w-full" style="height: 250px; overflow-y: scroll;">
                                                            @foreach($cluster->regions as $region)
                                                                <label class="flex items-center">
                                                                    <input type="checkbox" name="remove_order_region_ids[]" value="{{ $region->id }}" class="mr-2">
                                                                    {{ $region->name }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="remove_warehouse_ids-{{ $cluster->id }}" value="Удалить склады" />
                                                        <div id="remove_warehouse_ids-{{ $cluster->id }}" class="mt-1 block w-full" style="height: 250px; overflow-y: scroll;">
                                                            @foreach($cluster->warehouses as $warehouse)
                                                                <label class="flex items-center">
                                                                    <input type="checkbox" name="remove_warehouse_ids[]" value="{{ $warehouse->id }}" class="mr-2">
                                                                    {{ $warehouse->name }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <x-danger-button>Удалить выбранное</x-danger-button>
                                                </form>
                                                <form method="post" action="{{ route('settings.deleteCluster', $cluster->id) }}" class="mt-6 space-y-6">
                                                    @csrf
                                                    @method('delete')
                                                    <x-danger-button>Удалить кластер</x-danger-button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleCluster(id) {
            var element = document.getElementById('cluster-' + id);
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
