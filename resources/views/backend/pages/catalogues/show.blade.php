@extends('backend.layouts.master')

@section('title', $catalog->name . ' - ' . getSetting('system_title'))

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ $catalog->name }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="pdf-viewer"></div>

                            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.12.377/pdf.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.12.377/pdf.worker.js"></script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var url = '{{ route("admin.catalogues.displayPdf", ["id" => $catalog->id]) }}';

                                    var pdfjsLib = window['pdfjs-dist/build/pdf'];
                                    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.12.377/pdf.worker.js';

                                    var loadingTask = pdfjsLib.getDocument(url);
                                    loadingTask.promise.then(function (pdf) {
                                        pdf.getPage(1).then(function (page) {
                                            var scale = 1.5;
                                            var viewport = page.getViewport({ scale: scale });

                                            var canvas = document.createElement('canvas');
                                            var context = canvas.getContext('2d');
                                            canvas.height = viewport.height;
                                            canvas.width = viewport.width;

                                            var renderContext = {
                                                canvasContext: context,
                                                viewport: viewport
                                            };
                                            var renderTask = page.render(renderContext);
                                            renderTask.promise.then(function () {
                                                document.getElementById('pdf-viewer').appendChild(canvas);
                                            });
                                        });
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
