@extends('layouts.template')

@section('title', 'Detail Pengepul')

@push('style')
    <style>
        .card-container {
            display: flex;
            gap: 20px;
            justify-content: space-between;
        }

        .drag-card {
            width: 100%;
            min-height: 300px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 15px;
            background-color: #f9f9f9;
            flex: 1;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .drag-card h5 {
            margin-bottom: 15px;
            font-weight: bold;
            color: #333;
        }

        .drag-item {
            padding: 12px 18px;
            margin-bottom: 10px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: grab;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .drag-item:hover {
            background-color: #f0f8ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .drag-item.dragging {
            opacity: 0.6;
            border-color: #007bff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            cursor: grabbing;
        }

        .drag-over {
            background-color: #e0f7fa !important;
            border-color: #007bff !important;
        }

        #drag-status-message {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #eee;
            background-color: #f0f0f0;
            border-radius: 5px;
            color: #555;
            text-align: center;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Pengepul</h3>
        </div>
    </div>

    <div class="card-container">
        <!-- Anggota Luar Cabang -->
        <div class="drag-card" id="anggota-luar">
            <h5>Anggota Luar Cabang</h5>
            @foreach($anggotaLuar as $anggota)
                <div class="drag-item" id="anggota-luar-{{ $anggota->id }}" draggable="true" data-id="{{ $anggota->id }}">
                    {{ $anggota->nama }}
                </div>
            @endforeach
        </div>

        <!-- Anggota Cabang -->
        <div class="drag-card" id="anggota-cabang">
            <h5>Anggota Cabang</h5>
            @foreach($anggotaCabang as $anggota)
                <div class="drag-item" id="anggota-cabang-{{ $anggota->id }}" draggable="true" data-id="{{ $anggota->id }}">
                    {{ $anggota->nama }}
                </div>
            @endforeach
        </div>
    </div>

    <p id="drag-status-message"></p>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dragStatusMessage = document.getElementById('drag-status-message');

            function drag(ev) {
                ev.dataTransfer.setData("text/plain", ev.target.id);
                ev.dataTransfer.effectAllowed = "move";
                ev.target.classList.add('dragging');
                if (dragStatusMessage) {
                    dragStatusMessage.innerHTML = `Mulai menyeret: <strong>${ev.target.innerText}</strong>. Lepaskan untuk memindahkan.`;
                }
            }

            function allowDrop(ev) {
                ev.preventDefault();
                ev.dataTransfer.dropEffect = "move";
                ev.currentTarget.classList.add('drag-over');
            }

            function dragLeave(ev) {
                ev.currentTarget.classList.remove('drag-over');
            }

            function dragEnd(ev) {
                ev.target.classList.remove('dragging');
                if (dragStatusMessage && ev.dataTransfer.dropEffect === "none") {
                    dragStatusMessage.innerHTML = `Seretan dibatalkan untuk <strong>${ev.target.innerText}</strong>.`;
                }
            }

            function drop(ev) {
                ev.preventDefault();

                const id = ev.dataTransfer.getData("text/plain");
                const draggableElement = document.getElementById(id);
                const dropZone = ev.currentTarget;

                if (draggableElement && dropZone !== draggableElement.parentNode) {
                    dropZone.appendChild(draggableElement);
                    if (dragStatusMessage) {
                        console.log("draggableElement",draggableElement);
                        
                        dragStatusMessage.innerHTML = `<strong>${draggableElement.innerText}</strong> berhasil dipindahkan ke <strong>${dropZone.querySelector('h5').innerText}</strong>.`;
                    }
                } else if (draggableElement && dragStatusMessage) {
                    dragStatusMessage.innerHTML = `<strong>${draggableElement.innerText}</strong> dijatuhkan di lokasi yang sama.`;
                }

                draggableElement.classList.remove('dragging');
                dropZone.classList.remove('drag-over');
            }

            function refreshListeners() {
                document.querySelectorAll('.drag-item').forEach(item => {
                    item.removeEventListener('dragstart', drag);
                    item.removeEventListener('dragend', dragEnd);
                    item.addEventListener('dragstart', drag);
                    item.addEventListener('dragend', dragEnd);
                });
            }

            // Inisialisasi
            document.querySelectorAll('.drag-item').forEach(item => {
                item.addEventListener('dragstart', drag);
                item.addEventListener('dragend', dragEnd);
            });

            document.querySelectorAll('.drag-card').forEach(card => {
                card.addEventListener('dragover', allowDrop);
                card.addEventListener('drop', function (ev) {
                    drop(ev);
                    refreshListeners(); // Pastikan listener tetap aktif setelah elemen dipindahkan
                });
                card.addEventListener('dragleave', dragLeave);
            });
        });
    </script>
@endsection
