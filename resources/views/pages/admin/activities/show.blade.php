@extends('layouts.template')

@section('title', 'Detail Activity')

@push('style')
    <style>
        /* Card untuk activity */
        .activity-card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 20px;
            background: #fff;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .activity-image {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .activity-image:hover {
            transform: scale(1.05);
        }

        .activity-label,
        .activity-status {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 12px;
            color: #fff;
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .activity-status.active {
            background-color: #28a745;
        }

        .activity-status.inactive {
            background-color: #dc3545;
        }

        .activity-info {
            display: grid;
            grid-template-columns: 1fr 2fr;
            row-gap: 10px;
            column-gap: 20px;
            align-items: start;
            margin-bottom: 20px;
        }

        .activity-info h5 {
            font-weight: 600;
            margin-bottom: 0;
        }

        .activity-info p {
            margin: 0;
        }

        /* Box untuk deskripsi dan konten */
        .activity-text-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
            line-height: 1.6;
            overflow: hidden;
        }

        .activity-text-box h6 {
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* Responsif untuk embed video */
        .activity-text-box iframe,
        .activity-text-box video {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 10px;
        }

        /* Untuk responsive ratio 16:9 (YouTube) */
        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 */
            height: 0;
            overflow: hidden;
            border-radius: 10px;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .activity-text-box iframe {
            display: block;
            width: 100% !important;
            height: 400px !important;
            border: none;
            border-radius: 10px;
        }
    </style>
@endpush

@section('main')
    <div class="row">
        <div class="col-lg-12">
            <div class="card activity-card">
                <div class="activity-header">
                    <h4>Detail Activity</h4>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                @if ($activity->image)
                    <img src="{{ asset($activity->image) }}" alt="Activity Image" class="activity-image">
                @endif

                <div class="activity-info">
                    <h5>Title</h5>
                    <p>{{ $activity->title }}</p>

                    <h5>Start Date</h5>
                    <p>{{ $activity->start_date ?? '-' }}</p>

                    <h5>End Date</h5>
                    <p>{{ $activity->end_date ?? '-' }}</p>

                    <h5>Location</h5>
                    <p>{{ $activity->location ?? '-' }}</p>

                    <h5>Label</h5>
                    @if ($activity->label)
                        <span class="activity-label" style="background-color: {{ $activity->label->color ?? '#333' }}">
                            {{ $activity->label->name }}
                        </span>
                    @else
                        <p><em>-</em></p>
                    @endif

                    <h5>Status</h5>
                    <span class="activity-status {{ $activity->status }}">
                        {{ ucfirst($activity->status) }}
                    </span>
                </div>

                {{-- Description dan Content tampil di bawah --}}
                <div class="mt-4">
                    <h5>Description</h5>
                    <div class="activity-text-box mb-3">
                        {!! $activity->description ?? '<em>-</em>' !!}
                    </div>

                    <h5>Content</h5>
                    <div class="activity-text-box">
                        {{-- Deteksi jika ada embed YouTube --}}
                        @if (Str::contains($activity->content, 'youtube.com') || Str::contains($activity->content, 'youtu.be'))
                            <div class="video-wrapper">
                                {!! $activity->content !!}
                            </div>
                        @else
                            {!! $activity->content ?? '<em>-</em>' !!}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
