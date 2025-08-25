<table class="table table-sm table-bordered table-bordered dt-responsive nowrap"
    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Mr Date</th>
            <th>Executive</th>
            <th>Name</th>
            <th>Student ID</th>
            <th>Batch|Course</th>
            <th>Mr No</th>
            <th>Type</th>
            <th>Paid</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0;@endphp
        @forelse ($results as $key => $report)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($report->paymentDate)->format('d M, Y') }}</td>
                <td>{{ $report->exName }}</td>
                <td>{{ $report->sName }}</td>
                <td>{{ $report->sId }}</td>
                <td>
                    @if ($report->batch_id)
                        {{ \DB::table('batches')->where('id', $report->batch_id)->first()->batchId }}
                    @else
                        {{ \DB::table('courses')->where('id', $report->course_id)->first()->courseName }}
                    @endif
                </td>
                <td>{{ $report->mrNo }}</td>
                <td>Registration</td>
                <td>{{-- \Carbon\Carbon::parse($report->entryDate)->format('d M, Y') --}}{{$report->total_paid}}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No data found for the selected filters.</td>
            </tr>
        @endforelse
    </tbody>

</table>
            
