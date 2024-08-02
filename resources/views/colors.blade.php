<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DaisyUI Color Palette</title>
    @vite('resources/css/app.css')
    <style>
        .color-box {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body class="p-10 bg-base-200">
    <div class="container mx-auto">
        <h1 class="mb-6 text-3xl font-bold">DaisyUI Color Palette</h1>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            <div class="text-center">
                <div class="color-box bg-primary"></div>
                <p>primary</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-secondary"></div>
                <p>secondary</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-accent"></div>
                <p>accent</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-neutral"></div>
                <p>neutral</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-base-100"></div>
                <p>base-100</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-base-200"></div>
                <p>base-200</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-base-300"></div>
                <p>base-300</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-info"></div>
                <p>info</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-success"></div>
                <p>success</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-warning"></div>
                <p>warning</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-error"></div>
                <p>error</p>
            </div>
            <div class="text-center">
                <div class="color-box bg-base-content"></div>
                <p>base-content</p>
            </div>
            <div class="text-center">
                <div class="color-box text-accent"></div>
                <p class="text-accent">text-accent</p>
            </div>
        </div>
    </div>

    <div class="px-1 px-2 md:px-2 md:px-1" /></body>
    <div class="progress progress-primary"></div>
    <div class="progress progress-secondary"></div>
    <div class="progress progress-accent"></div>
    <div class="progress progress-info"></div>
    <div class="progress progress-success"></div>
    <div class="progress progress-warning"></div>
    <div class="progress progress-error"></div>
</body>
</html>
