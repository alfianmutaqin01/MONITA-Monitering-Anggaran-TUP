@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="">
  <p>Selamat datang, {{ session('user_data.nama_pp') ?? session('user_data.username') }} (Role: {{ session('user_data.role') }})</p>
    <h2 class="mb-4">Detail Anggaran</h2>

    <div class="card mb-4">
        <div class="card-body">
            

            <div class="table-responsive mt-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>First</th>
                            <th>Last</th>
                            <th>Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><th>1</th><td>Mark</td><td>Otto</td><td>@mdo</td></tr>
                        <tr><th>2</th><td>Jacob</td><td>Thornton</td><td>@fat</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
