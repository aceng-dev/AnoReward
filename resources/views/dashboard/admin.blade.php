@extends('layouts.app')

@section('content')

<div class="container">

    <h1>Executive Dashboard</h1>

    <div class="row">

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Total Developer</h5>
                <h2>{{ $totalDeveloper }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Approved Tasks</h5>
                <h2>{{ $approvedTasks }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Average Productivity</h5>
                <h2>{{ number_format($averageProductivity, 2) }}</h2>
            </div>
        </div>

    </div>

    <br>

    <div class="card p-3">

        <h3>Top Performers</h3>

        <table class="table">

            <thead>
                <tr>
                    <th>Developer</th>
                    <th>Average Score</th>
                </tr>
            </thead>

            <tbody>

                @foreach($topPerformers as $dev)

                <tr>
                    <td>{{ $dev->name }}</td>

                    <td>
                        {{ number_format($dev->tasks_avg_task_score, 2) }}
                    </td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <br>

    <div class="card p-3">

        <h3>Reward Decision Center</h3>

        <table class="table">

            <thead>
                <tr>
                    <th>Developer</th>
                    <th>Average Score</th>
                    <th>Recommendation</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach($recommendations as $rec)

                <tr>

                    <td>{{ $rec->user->name }}</td>

                    <td>{{ $rec->average_score }}</td>

                    <td>

                        @if($rec->recommendation_status == 'eligible')

                            <span class="badge bg-success">
                                Eligible
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Not Eligible
                            </span>

                        @endif

                    </td>

                    <td>{{ $rec->approval_status }}</td>

                    <td>

                        <form method="POST"
                            action="/po/recommendation/{{ $rec->id }}/approve">

                            @csrf

                            <button class="btn btn-success btn-sm">
                                Approve
                            </button>

                        </form>

                        <br>

                        <form method="POST"
                            action="/po/recommendation/{{ $rec->id }}/reject">

                            @csrf

                            <button class="btn btn-danger btn-sm">
                                Reject
                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection