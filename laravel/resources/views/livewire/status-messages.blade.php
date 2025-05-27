<div
    x-data="{
        messages: @entangle('messages'),
        removeMessage() {
            @this.call('removeFirstMessage');
        }
    }"
    x-init="
        $watch('messages', value => {
            if (value.length) {
                setTimeout(() => removeMessage(), 3000);
            }
        });
    "
    class="fixed top-4 right-4 space-y-2 z-50"
>
    <template x-for="(message, index) in messages" :key="index">
        <div class="bg-blue-500 text-white px-4 py-2 rounded shadow">
            <span x-text="message"></span>
        </div>
    </template>
</div>

