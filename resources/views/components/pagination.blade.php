 <div class="card-footer flex-between flex-wrap">
     <span class="text-gray-900">
         Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of
         {{ $data->total() }} entries
     </span>

     @if ($data->hasPages())
         <ul class="pagination flex-align flex-wrap">
             {{-- Previous Page --}}
             @if ($data->onFirstPage())
                 <li class="page-item disabled">
                     <span class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium">&laquo;</span>
                 </li>
             @else
                 <li class="page-item">
                     <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium"
                         href="{{ $data->previousPageUrl() }}" rel="prev">&laquo;</a>
                 </li>
             @endif

             {{-- Page Number Links --}}
             @php
                 $current = $data->currentPage();
                 $last = $data->lastPage();
                 $start = max(1, $current - 2);
                 $end = min($last, $current + 2);
             @endphp

             @if ($start > 1)
                 <li class="page-item">
                     <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium"
                         href="{{ $data->url(1) }}">1</a>
                 </li>
                 @if ($start > 2)
                     <li class="page-item"><span
                             class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium">...</span>
                     </li>
                 @endif
             @endif

             @for ($i = $start; $i <= $end; $i++)
                 @if ($i == $current)
                     <li class="page-item active">
                         <span
                             class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium">{{ $i }}</span>
                     </li>
                 @else
                     <li class="page-item">
                         <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium"
                             href="{{ $data->url($i) }}">{{ $i }}</a>
                     </li>
                 @endif
             @endfor

             @if ($end < $last)
                 @if ($end < $last - 1)
                     <li class="page-item"><span
                             class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium">...</span>
                     </li>
                 @endif
                 <li class="page-item">
                     <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium"
                         href="{{ $data->url($last) }}">{{ $last }}</a>
                 </li>
             @endif

             {{-- Next Page --}}
             @if ($data->hasMorePages())
                 <li class="page-item">
                     <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium"
                         href="{{ $data->nextPageUrl() }}" rel="next">&raquo;</a>
                 </li>
             @else
                 <li class="page-item disabled">
                     <span class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium">&raquo;</span>
                 </li>
             @endif
         </ul>
     @endif
 </div>
