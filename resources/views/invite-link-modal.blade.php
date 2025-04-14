<div class="p-4">
    <p class="text-sm font-medium text-gray-700">Link Undangan:</p>

    <div class="flex items-center space-x-2 mt-2">
        <input id="inviteLink" type="text" value="{{ $inviteLink }}" readonly
            class="w-full border rounded-lg p-2 bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button onclick="copyToClipboard()"
            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
            Salin
        </button>
    </div>

    <p id="copy-success" class="hidden text-green-600 text-sm mt-2">Link berhasil disalin!</p>
</div>

<script>
    function copyToClipboard() {
        let inviteLinkInput = document.getElementById('inviteLink');
        inviteLinkInput.select();
        inviteLinkInput.setSelectionRange(0, 99999); // Untuk mendukung mobile
        navigator.clipboard.writeText(inviteLinkInput.value).then(() => {
            document.getElementById('copy-success').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('copy-success').classList.add('hidden');
            }, 2000);
        });
    }
    document.addEventListener("DOMContentLoaded", () => {
        copyToClipboard();
    });
</script>
