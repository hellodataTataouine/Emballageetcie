@extends('backend.layouts.master')

@section('title')
    {{ localize('Catalogues') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Catalogues') }}</h2>
                            </div>
                            <div class="tt-action">
                                @can('add_catalogues')
                                    <a href="{{ route('admin.catalogues.create') }}" class="btn btn-primary">
                                        <i data-feather="plus"></i> {{ localize('Ajouter Catalogue') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <h5 class="mb-3">{{ localize('Liste des catalogues') }}</h5>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ localize('Titre') }}</th>
                                        <th scope="col">{{ localize('Publié') }}</th>
                                        <th scope="col">{{ localize('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($catalogues as $key => $catalog)
                                        <tr>
                                            <th scope="row">{{ $key + 1 }}</th>
                                            <td>
                                                <a href="{{ route('admin.catalogues.show', $catalog->id) }}" target="_blank">
                                                    {{ $catalog->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" onchange="updatePublishedStatus(this)"
                                                        class="form-check-input"
                                                        @if ($catalog->is_published) checked @endif
                                                        value="{{ $catalog->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown tt-tb-dropdown">
                                                    <button type="button" class="btn p-0" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end shadow">
                                                        @can('edit_catalogues')
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.catalogues.edit', ['id' => $catalog->id]) }}">
                                                                <i data-feather="edit-3"
                                                                        class="me-2"></i>{{ localize('Modifier') }}
                                                            </a>
                                                        @endcan

                                                        <form action="{{ route('admin.catalogues.delete', $catalog->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('{{ localize('Are you sure you want to delete this catalog?') }}')"
                                                                title="{{ localize('Supprimer') }}">
                                                                    <i data-feather="trash-2" class="me-2"></i>
                                                                    {{ localize('Supprimer') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">{{ localize('Aucun catalogue trouvé.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function updatePublishedStatus(el) {
            var status = el.checked ? 1 : 0;
            $.post('{{ route('admin.catalogues.togglePublishStatus', ['id' => ':id']) }}'.replace(':id', el.value), {
                _token: '{{ csrf_token() }}',
                is_published: status
            }, function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    alert('{{ localize('Failed to update publish status') }}');
                }
            });
        }
    </script>
@endsection
