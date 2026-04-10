@extends('layouts.app')

@section('content')
@php
    $isEditing = isset($recipe) && $recipe->exists;
    $pageTitle = $isEditing ? 'Actualitza la teva recepta' : 'Comparteix el teu secret culinari';
    $cardTitle = $isEditing ? 'Editar Recepta' : 'Formulari de Recepta';
    $cardSubtitle = $isEditing
        ? 'Actualitza els ingredients, el temps i la imatge de la teva recepta'
        : 'Detalla els ingredients i passos de la teva recepta';
    $formAction = $isEditing ? route('recipes.update', $recipe) : route('recipes.store');
    $backUrl = $isEditing ? route('profile.show') : route('recipes.index');
    $submitLabel = $isEditing ? 'Desar canvis' : 'Publicar Recepta';
    $selectedDifficulty = old('difficulty', $recipe->difficulty ?? '');
    $tagsValue = old('tags', isset($recipe) && is_array($recipe->tags) ? implode(', ', $recipe->tags) : '');
    $ingredientsValue = old('ingredients', isset($recipe) && is_array($recipe->ingredients) ? implode("\n", $recipe->ingredients) : '');
    $stepsValue = old('steps', isset($recipe) && is_array($recipe->steps) ? implode("\n", $recipe->steps) : '');
@endphp
<div class="section-ui">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-start gap-4 mx-auto mb-5" style="max-width: 800px; width: 100%;">
        <div class="text-center flex-grow-1">
            <h1 class="recipe-hero-title mb-0">
                {{ $pageTitle }}
            </h1>
        </div>
    </div>

    <!-- CARD FORMULARI -->
    <div class="recipe-form-container">

        <!-- HEADER DE LA TARGETA -->
        <div class="recipe-form-header-box">
            <div class="flex-grow-1">
                <h2 class="recipe-form-title">{{ $cardTitle }}</h2>
                <p class="recipe-form-subtitle">{{ $cardSubtitle }}</p>
            </div>
            <a href="{{ $backUrl }}" class="btn-secondary-ui">
                <i class="bi bi-arrow-left"></i> TORNAR
            </a>
        </div>

        <!-- CONTINGUT DEL FORMULARI -->
        <div class="recipe-form-body">

            <!-- ALERTES D'ERROR -->
            @if ($errors->any())
                <div class="recipe-alert-error">
                    <div class="d-flex align-items-center gap-2 fw-semibold" style="font-size: 14px;">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Error a la validació</span>
                    </div>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-1" style="font-size: 13px;">
                        @foreach ($errors->all() as $error)
                            <li class="position-relative lh-base ps-3">
                                <span class="position-absolute start-0">•</span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulari reutilitzat per crear i editar receptes -->
            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="edit-form d-flex flex-column gap-4">
                @csrf
                @if($isEditing)
                    @method('PATCH')
                @endif

                <!-- TÍTOL -->
                <div class="recipe-form-group">
                    <label for="title" class="recipe-form-label">Títol de la Recepta</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Ex: Risotto de bolets salvatges"
                        value="{{ old('title', $recipe->title ?? '') }}"
                        required
                        class="recipe-form-input @error('title') recipe-form-error @enderror"
                    >
                    @error('title')
                        <div class="recipe-form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- FILA: DIFICULTAT I TEMPS -->
                <div class="row g-4">
                    <div class="col-md-6 recipe-form-group">
                        <label for="difficulty" class="recipe-form-label">Dificultat</label>
                        <select
                            id="difficulty"
                            name="difficulty"
                            required
                            class="recipe-form-select @error('difficulty') recipe-form-error @enderror"
                        >
                            <option value="">-- Selecciona --</option>
                            <option value="fàcil" {{ $selectedDifficulty === 'fàcil' ? 'selected' : '' }}>Fàcil</option>
                            <option value="mitjà" {{ $selectedDifficulty === 'mitjà' ? 'selected' : '' }}>Mitjà</option>
                            <option value="difícil" {{ $selectedDifficulty === 'difícil' ? 'selected' : '' }}>Difícil</option>
                        </select>
                        @error('difficulty')
                            <div class="recipe-form-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 recipe-form-group">
                        <label for="cooking_time" class="recipe-form-label">Temps de Cocció</label>
                        <div class="position-relative d-flex align-items-center">
                            <i class="bi bi-clock position-absolute text-muted px-3 z-1 pe-none"></i>
                            <input
                                type="number"
                                id="cooking_time"
                                name="cooking_time"
                                placeholder="Ex: 45 minuts"
                                value="{{ old('cooking_time', $recipe->cooking_time ?? '') }}"
                                min="1"
                                max="1000"
                                required
                                class="recipe-form-input has-icon @error('cooking_time') recipe-form-error @enderror"
                            >
                        </div>
                        @error('cooking_time')
                            <div class="recipe-form-error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- ETIQUETES -->
                <div class="recipe-form-group">
                    <label for="tags" class="recipe-form-label">Etiquetes</label>
                    <input
                        type="text"
                        id="tags"
                        name="tags"
                        placeholder="Ex: Vegè, Italià, Sucre (separades per comes)"
                        value="{{ $tagsValue }}"
                        class="recipe-form-input @error('tags') recipe-form-error @enderror"
                    >
                    @error('tags')
                        <div class="recipe-form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- DESCRIPCIÓ -->
                <div class="recipe-form-group">
                    <label for="description" class="recipe-form-label">Descripció</label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Explica la història darrere d'aquest plat..."
                        rows="4"
                        class="recipe-form-textarea @error('description') recipe-form-error @enderror"
                    >{{ old('description', $recipe->description ?? '') }}</textarea>
                    @error('description')
                        <div class="recipe-form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- INGREDIENTS -->
                <div class="recipe-form-group">
                    <label for="ingredients" class="recipe-form-label">Ingredients</label>
                    <textarea
                        id="ingredients"
                        name="ingredients"
                        placeholder="Un ingredient per línia&#10;Ex: 320g d'arròs Carnaroli&#10;1.5L de brou de verdures"
                        rows="5"
                        class="recipe-form-textarea @error('ingredients') recipe-form-error @enderror"
                    >{{ $ingredientsValue }}</textarea>
                    <p class="small text-secondary mt-1 mb-0">Escriu cada ingredient en una nova línia</p>
                    @error('ingredients')
                        <div class="recipe-form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- PASSOS A SEGUIR -->
                <div class="recipe-form-group">
                    <label for="steps" class="recipe-form-label">Passos a seguir</label>
                    <textarea
                        id="steps"
                        name="steps"
                        placeholder="Escriu un pas per línia&#10;Ex: Talla les verdures a daus petits&#10;Sofregeix a foc lent durant 10 minuts"
                        rows="6"
                        class="recipe-form-textarea @error('steps') recipe-form-error @enderror"
                    >{{ $stepsValue }}</textarea>
                    <p class="small text-secondary mt-1 mb-0">Escriu cada pas en una nova línia</p>
                    @error('steps')
                        <div class="recipe-form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- IMATGE -->
                <div class="recipe-form-group">
                    <label for="image" class="recipe-form-label">Imatge de la Recepta</label>
                    <div id="imageDropZone" class="recipe-form-dropzone">
                        @if($isEditing && !empty($recipe->image))
                            <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="position-absolute w-100 h-100 object-fit-cover">
                            <div class="position-absolute w-100 h-100" style="background: rgba(255, 255, 255, 0.72);"></div>
                        @endif
                        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" style="display: none;">
                        <div class="text-center position-relative z-1 pe-none">
                            <div class="fs-1 text-secondary mb-3">
                                <i class="bi bi-image"></i>
                            </div>
                            <p id="imageUploadPrimaryText" class="fw-medium text-dark mb-1 fs-6">
                                {{ $isEditing ? 'Fes clic per substituir la imatge actual' : 'Fes clic per pujar una imatge' }}
                            </p>
                            <p id="imageUploadSecondaryText" class="small text-secondary mb-0">PNG, JPG o JPEG fins a 2MB</p>
                        </div>
                    </div>
                    <div id="imageUploadStatus" aria-live="polite" class="d-none align-items-center gap-2 text-success fw-semibold mt-1" style="font-size: 12px;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span id="imageUploadStatusText"></span>
                    </div>
                    @if($isEditing && !empty($recipe->image))
                        <p class="small text-secondary mt-1 mb-0">Si no selecciones cap fitxer nou, mantindrem la imatge actual.</p>
                    @endif
                    @error('image')
                        <div class="recipe-form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notes del xef visibles també durant la creació per alimentar el detall de la recepta -->
                <div class="recipe-form-group">
                    <label for="chef_notes" class="recipe-form-label">Notes del Xef (opcional)</label>
                    <textarea
                        id="chef_notes"
                        name="chef_notes"
                        placeholder="Afegeix un consell final, un truc de cocció o una recomanació de presentació..."
                        rows="2"
                        class="recipe-form-textarea @error('chef_notes') recipe-form-error @enderror"
                    >{{ old('chef_notes', $recipe->chef_notes ?? '') }}</textarea>
                    @error('chef_notes')
                        <div class="recipe-form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- BOTONS D'ACCIÓ -->
                <div class="d-flex align-items-center gap-3 mt-3 pt-4 border-top">
                    <button
                        type="submit"
                        class="btn-primary-ui"
                    >
                        <i class="bi bi-check-lg"></i>
                        <span>{{ $submitLabel }}</span>
                    </button>
                    <a
                        href="{{ $backUrl }}"
                        class="btn-secondary-ui"
                    >
                        <i class="bi bi-x-lg"></i>
                        <span>Cancel·lar</span>
                    </a>
                    @if($isEditing)
                        <!-- Eliminació de la recepta des del mateix formulari d'edició -->
                        <button
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteRecipeModal"
                            class="btn btn-outline-danger ms-auto rounded-3 d-inline-flex align-items-center gap-2 fw-semibold"
                            style="height: 44px; padding: 0 24px;"
                        >
                            <i class="bi bi-x-lg"></i>
                            <span>Eliminar recepta</span>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

</div>

@if($isEditing)
    <!-- Confirmació abans d'eliminar una recepta des del formulari d'edició -->
    <div class="modal fade" id="deleteRecipeModal" tabindex="-1" aria-labelledby="deleteRecipeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content danger-modal-content">
                <form method="POST" action="{{ route('recipes.destroy', $recipe) }}">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header danger-modal-header">
                        <h5 class="modal-title text-danger d-flex align-items-center gap-2" id="deleteRecipeModalLabel">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Confirmar eliminació
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small mb-0">
                            Vols eliminar definitivament la recepta <strong>{{ $recipe->title }}</strong>? També desapareixerà del teu rebost i del llistat general de receptes.
                        </p>
                    </div>

                    <div class="modal-footer danger-modal-footer">
                        <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                            Cancel·lar
                        </button>
                        <button type="submit" class="btn btn-danger danger-btn-rounded">
                            <i class="bi bi-trash3 me-1"></i>
                            Eliminar recepta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
    // Gestió mínima del dropzone per mantenir el layout actual de la pujada d'imatge.
    const imageInput = document.getElementById('image');
    const dropZone = document.getElementById('imageDropZone');
    const imageUploadStatus = document.getElementById('imageUploadStatus');
    const imageUploadStatusText = document.getElementById('imageUploadStatusText');
    const imageUploadPrimaryText = document.getElementById('imageUploadPrimaryText');
    const imageUploadSecondaryText = document.getElementById('imageUploadSecondaryText');

    const updateImageUploadFeedback = (file) => {
        if (!file || !imageUploadStatus || !imageUploadStatusText || !imageUploadPrimaryText || !imageUploadSecondaryText) {
            return;
        }

        imageUploadPrimaryText.textContent = 'Imatge seleccionada correctament';
        imageUploadSecondaryText.textContent = file.name;
        imageUploadStatusText.textContent = 'L\'arxiu està llest per pujar.';
        imageUploadStatus.style.display = 'inline-flex';
        dropZone.style.borderColor = '#198754';
        dropZone.style.backgroundColor = '#f3fbf6';
    };

    if (imageInput && dropZone) {
        dropZone.addEventListener('click', () => imageInput.click());

        imageInput.addEventListener('change', () => {
            if (imageInput.files.length > 0) {
                updateImageUploadFeedback(imageInput.files[0]);
            }
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#be3144';
            dropZone.style.backgroundColor = '#fff5f5';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#d0d0d0';
            dropZone.style.backgroundColor = '#fafafa';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#d0d0d0';
            dropZone.style.backgroundColor = '#fafafa';

            if (e.dataTransfer.files.length > 0) {
                imageInput.files = e.dataTransfer.files;
                updateImageUploadFeedback(e.dataTransfer.files[0]);
            }
        });
    }
</script>
@endsection
