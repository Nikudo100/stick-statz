<x-app-layout>
    <h1>Отзывы</h1>
    <br>
    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
        <div class="bg-gray-100 p-6 rounded-lg shadow-md max-w-2xl mx-auto">
            <!-- Комментарий -->
            @foreach ($feedbacks as $feedback)
                <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg">{{ $feedback->user_name }}</h3>
                            <p class="text-gray-500 text-sm">
                                {{ Carbon\Carbon::parse($feedback->wb_created_at)->format('d-m-Y H:i') }}
                            </p>
                            <p class="mt-2 text-gray-700">{{ $feedback->text }}</p>
                            <div class="mt-2 flex items-center text-yellow-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M9.049 2.927a1 1 0 011.902 0l1.4 4.3a1 1 0 00.95.691h4.516a1 1 0 01.592 1.807l-3.656 2.658a1 1 0 00-.364 1.118l1.4 4.3a1 1 0 01-1.537 1.118L10 15.347l-3.656 2.658a1 1 0 01-1.537-1.118l1.4-4.3a1 1 0 00-.364-1.118L2.187 9.725a1 1 0 01.592-1.807h4.516a1 1 0 00.95-.691l1.4-4.3z" />
                                </svg>
                                <span class="ml-1 text-gray-700">{{ $feedback->product_valuation }}</span>
                            </div>
                            <!-- Форма ответа -->
                            @if (!$feedback->answer)
                                <form class="mt-4" method="POST" action="{{ route('feedbacks.update', $feedback) }}">
                                    <textarea name="answer" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                        rows="2" placeholder="Ваш ответ..."></textarea>
                                    <div class="mt-2 flex justify-end">
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Отправить</button>
                                    </div>
                                    @csrf
                                    @method('put')
                                </form>
                            @else
                                <div class="mt-5 ml-5">
                                    <h3 class="font-semibold text-lg">{{ $feedback->user?->name }}</h3>
                                    <p class="text-gray-500 text-sm">
                                        {{ Carbon\Carbon::parse($feedback->answer_at)->format('d-m-Y H:i') }}
                                    </p>
                                    <p class="mt-2 text-gray-700">{{ $feedback->answer }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
</x-app-layout>
