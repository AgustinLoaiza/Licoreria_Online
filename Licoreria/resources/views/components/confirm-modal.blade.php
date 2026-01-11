<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 id="confirmTitle" class="text-lg font-semibold text-gray-800 mb-2"></h3>
            <p id="confirmMessage" class="text-gray-600 mb-6"></p>

            <div class="flex space-x-3">
                <button onclick="closeConfirmModal()"
                        class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300">
                    Cancelar
                </button>
                <button id="confirmAction"
                        class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showConfirmModal(title, message, actionUrl, method = 'DELETE') {
        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMessage').textContent = message;

        const confirmBtn = document.getElementById('confirmAction');
        confirmBtn.onclick = function() {
            if (method === 'DELETE') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            } else {
                window.location.href = actionUrl;
            }
        };

        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    // Cerrar con ESC
    document.addEventListener('keyup', function(e) {
        if (e.key === "Escape") closeConfirmModal();
    });
</script>
