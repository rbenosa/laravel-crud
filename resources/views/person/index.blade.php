@extends('layout.app')
@section('title', 'Person')

@section('content')

<div class="card">

    <div class="card-body">

        <div class="card-title h2">
            <span class="fas fa-fw fa-users"></span> People
            <div class="float-end">
                <a href="{{ route('person.create') }}" class="btn btn-primary"> <span class="fas fa-fw fa-user-plus"></span> Add Person</a>
            </div>
        </div>

        <hr>

        <table class="table table-striped table-hover table-responsive">
            <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Organization count</th>
                <th>Date Created</th>
                <th></th>
            </thead>

            <tbody>

                @forelse($persons as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ count($item->organization) }}</td>
                    <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                    <td>
                        <div class="btn-group btn-group-sm float-end" role="group" aria-label="Basic mixed styles example">
                            <a href="{{ route('person.view', ['id' => $item->id]) }}" class="btn btn-secondary"><span class="fas fa-fw fa-eye"></span> View</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">
                        <h3>
                            No records
                        </h3>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>


        <div class="d-flex justify-content-center">
            {{ $persons->links() }}
        </div>

    </div>
</div>

@endsection