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
                                <x-text-input id="wb_token" name="wb_token" type="password" class="mt-1 block w-full"
                                    :value="$setting->wb_token" />
                                <x-input-error :messages="$errors->updatePassword->get('wb_token')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="auto_feedback_answer" value="Автоответы" />
                                <x-text-input value="1" :checked="$setting->auto_feedback_answer" id="auto_feedback_answer"
                                    name="auto_feedback_answer" type="checkbox" />
                                <x-input-error :messages="$errors->updatePassword->get('auto_feedback_answer')" class="mt-2" />
                            </div>
                            <x-primary-button> Сохранить</x-primary-button>
                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Автоответы
                            </h2>
                        </header>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Column 1 -->
                            <div class="bg-white p-6 rounded-lg shadow-lg">
                                <h2 class="text-2xl font-bold mb-2">3 звезды</h2>
                                <p class="text-gray-700 mb-4">Описание первой колонки. Здесь может быть любая
                                    информация, которую вы хотите разместить.</p>
                                </div>
                            <!-- Column 2 -->
                            <div class="bg-white p-6 rounded-lg shadow-lg">
                                <h2 class="text-2xl font-bold mb-2">4 звезды</h2>
                                <p class="text-gray-700 mb-4">Описание второй колонки. Здесь может быть любая
                                    информация, которую вы хотите разместить.</p>
                                </div>
                            <!-- Column 3 -->
                            <div class="bg-white p-6 rounded-lg shadow-lg">
                                <h2 class="text-2xl font-bold mb-2">5 звезд</h2>
                                <p class="text-gray-700 mb-4">Описание третьей колонки. Здесь может быть любая
                                    информация, которую вы хотите разместить.</p>
                                </div>
                        </div>
                        <form method="post" action="{{ route('autoAnswer.create') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('post')
                            <div>
                                <x-input-label for="answer" value="Ответ" />
                                <textarea id="answer" name="answer" type="password"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                                <x-input-error :messages="$errors->updatePassword->get('answer')" class="mt-2" />
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input id="radio-3" type="radio" name="stars" value="3"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <label for="radio-3" class="ml-2 text-sm font-medium text-gray-900">3</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="radio-4" type="radio" name="stars" value="4"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <label for="radio-4" class="ml-2 text-sm font-medium text-gray-900">4</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="radio-5" type="radio" name="stars" value="5"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <label for="radio-5" class="ml-2 text-sm font-medium text-gray-900">5</label>
                                </div>
                            </div>
                            <x-primary-button> Сохранить</x-primary-button>
                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
