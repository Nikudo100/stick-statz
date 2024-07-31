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
                    <br><br>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Настройки Кластеров
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
                    <br><br>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
