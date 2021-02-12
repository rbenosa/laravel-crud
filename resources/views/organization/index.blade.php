@extends('layout.app')
@section('title', 'Organization')

@section('content')

<div class="card">

    <div class="card-body">

        <div class="card-title h2">
            <span class="fas fa-fw fa-network-wired"></span> Organizations

            <div class="float-end">
                <a href="{{ route('organization.create') }}" class="btn btn-primary"> <span class="fas fa-fw fa-plus"></span> Add Organization</a>
            </div>
        </div>

        <hr>

        <table class="table table-striped table-hover table-responsive">
            <thead>
                <th>Name</th>
                <th>Member count</th>
                <th class="text-end">Date Created</th>
                <th></th>
            </thead>

            <tbody>
                @if(count($organizations) == 0)
                    <tr>
                        <td colspan="4" class="text-center">

                            <h3>
                                No records
                            </h3>
                        </td>
                    </tr>
                @else
                    @foreach($organizations as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ count($item->person) }}</td>
                            <td class="text-end">{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm float-end" role="group" aria-label="Basic mixed styles example">
                                    <a href="{{ route('organization.view', ['id' => $item->id]) }}" class="btn btn-secondary"><span class="fas fa-fw fa-eye"></span> View</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>


        <div class="d-flex justify-content-center">
            {{ $organizations->links() }}
        </div>


    </div>


</div>

@endsection
