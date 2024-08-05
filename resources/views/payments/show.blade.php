<x-guest-layout>
    <div class="container mx-auto min-h-screen">
        <div class="flex justify-center items-center h-screen">
            <div class="flex w-1/2 flex-col bg-white border shadow-sm rounded-xl p-4 md:p-5 max-w-50 mt-10">
                <h3 class="text-lg font-bold text-gray-800">
                    Tu pago en La vaca que más canta
                </h3>
                <p class="mt-1 mb-4 text-xs font-medium uppercase text-gray-500">
                    {{ $payment->description }}
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong>Referencia</strong>: {{ $payment->reference }}
                    </div>
                    <div>
                        <strong>Monto</strong>: {{ $payment->amount }} {{ $payment->currency }}
                    </div>
                    <div>
                        <strong>Estado</strong>: {{ $payment->status }}
                    </div>
                    <div>
                        <strong>Fecha</strong>: {{ $payment->created_at }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
