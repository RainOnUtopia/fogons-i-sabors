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
@endphp
<div style="width: 100vw; min-height: 100vh; background-color: #f4efea; background-image: linear-gradient(0deg, transparent 24%, rgba(200, 200, 200, 0.05) 25%, rgba(200, 200, 200, 0.05) 26%, transparent 27%, transparent 74%, rgba(200, 200, 200, 0.05) 75%, rgba(200, 200, 200, 0.05) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(200, 200, 200, 0.05) 25%, rgba(200, 200, 200, 0.05) 26%, transparent 27%, transparent 74%, rgba(200, 200, 200, 0.05) 75%, rgba(200, 200, 200, 0.05) 76%, transparent 77%, transparent); background-size: 50px 50px; padding: 40px 20px; margin: 0; margin-left: calc(-50vw + 50%); display: flex; flex-direction: column;">

    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; max-width: 800px; width: 100%; margin: 0 auto 40px; gap: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="font-family: Georgia, Garamond, serif; font-style: italic; font-size: 2.2rem; font-weight: 700; color: #2d2d2d; margin: 0; letter-spacing: -0.5px;">
                {{ $pageTitle }}
            </h1>
        </div>
    </div>

    <!-- CARD FORMULARI -->
    <div style="max-width: 800px; width: 100%; margin: 0 auto; background: white; border-radius: 24px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); overflow: hidden; display: flex; flex-direction: column;">

        <!-- HEADER DE LA TARGETA -->
        <div style="padding: 32px 36px; border-bottom: 1px solid #e8e8e8; display: flex; justify-content: space-between; align-items: center; gap: 20px;">
            <div style="flex: 1;">
                <h2 style="font-size: 24px; font-weight: 600; color: #2d2d2d; margin: 0 0 4px; padding: 0;">{{ $cardTitle }}</h2>
                <p style="font-size: 14px; color: #7a7a7a; margin: 0; padding: 0;">{{ $cardSubtitle }}</p>
            </div>
            <a href="{{ $backUrl }}" style="height: 40px; padding: 0 16px; background-color: transparent; color: #7a7a7a; border: 1px solid #d0d0d0; border-radius: 12px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;">
                <i class="bi bi-arrow-left"></i> TORNAR
            </a>
        </div>

        <!-- CONTINGUT DEL FORMULARI -->
        <div style="padding: 32px 36px; flex: 1;">

            <!-- ALERTES D'ERROR -->
            @if ($errors->any())
                <div style="padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; flex-direction: column; gap: 12px; background-color: #fff5f5; border: 1px solid #f8d7da; color: #be3144;">
                    <div style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 14px;">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Error a la validació</span>
                    </div>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px;">
                        @foreach ($errors->all() as $error)
                            <li style="padding-left: 20px; position: relative; line-height: 1.4;">
                                <span style="position: absolute; left: 8px;">•</span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulari reutilitzat per crear i editar receptes -->
            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="edit-form" style="display: flex; flex-direction: column; gap: 24px;">
                @csrf
                @if($isEditing)
                    @method('PATCH')
                @endif

                <!-- TÍTOL -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label for="title" style="font-size: 12px; font-weight: 600; color: #9a9a9a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Títol de la Recepta</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Ex: Risotto de bolets salvatges"
                        value="{{ old('title', $recipe->title ?? '') }}"
                        required
                        style="width: 100%; height: 48px; padding: 12px 16px; background-color: #f3f3f3; border: none; border-radius: 12px; font-size: 14px; color: #2d2d2d; transition: all 0.3s ease; font-family: inherit; @error('title') background-color: #fff5f5; border: 1px solid #f8d7da; @enderror"
                    >
                    @error('title')
                        <div style="color: #be3144; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- FILA: DIFICULTAT I TEMPS -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <label for="difficulty" style="font-size: 12px; font-weight: 600; color: #9a9a9a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Dificultat</label>
                        <select
                            id="difficulty"
                            name="difficulty"
                            required
                            style="height: 48px; padding: 12px 16px; background-color: #f3f3f3; border: none; border-radius: 12px; font-size: 14px; color: #2d2d2d; font-family: inherit; cursor: pointer; transition: all 0.3s ease; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2712%27 height=%278%27 viewBox=%270 0 12 8%27%3E%3Cpath fill=%27%239A9A9A%27 d=%27M1 1l5 5 5-5%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; @error('difficulty') background-color: #fff5f5; border: 1px solid #f8d7da; @enderror"
                        >
                            <option value="">-- Selecciona --</option>
                            <option value="fàcil" {{ $selectedDifficulty === 'fàcil' ? 'selected' : '' }}>Fàcil</option>
                            <option value="mitjà" {{ $selectedDifficulty === 'mitjà' ? 'selected' : '' }}>Mitjà</option>
                            <option value="difícil" {{ $selectedDifficulty === 'difícil' ? 'selected' : '' }}>Difícil</option>
                        </select>
                        @error('difficulty')
                            <div style="color: #be3144; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <label for="cooking_time" style="font-size: 12px; font-weight: 600; color: #9a9a9a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Temps de Cocció</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="bi bi-clock" style="position: absolute; left: 16px; font-size: 16px; color: #9a9a9a; pointer-events: none; z-index: 1;"></i>
                            <input
                                type="number"
                                id="cooking_time"
                                name="cooking_time"
                                placeholder="Ex: 45 minuts"
                                value="{{ old('cooking_time', $recipe->cooking_time ?? '') }}"
                                min="1"
                                max="1000"
                                required
                                style="width: 100%; height: 48px; padding: 12px 16px 12px 48px; background-color: #f3f3f3; border: none; border-radius: 12px; font-size: 14px; color: #2d2d2d; transition: all 0.3s ease; font-family: inherit; @error('cooking_time') background-color: #fff5f5; border: 1px solid #f8d7da; @enderror"
                            >
                        </div>
                        @error('cooking_time')
                            <div style="color: #be3144; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- ETIQUETES -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label for="tags" style="font-size: 12px; font-weight: 600; color: #9a9a9a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Etiquetes</label>
                    <input
                        type="text"
                        id="tags"
                        name="tags"
                        placeholder="Ex: Vegè, Italià, Sucre (separades per comes)"
                        value="{{ $tagsValue }}"
                        style="width: 100%; height: 48px; padding: 12px 16px; background-color: #f3f3f3; border: none; border-radius: 12px; font-size: 14px; color: #2d2d2d; transition: all 0.3s ease; font-family: inherit; @error('tags') background-color: #fff5f5; border: 1px solid #f8d7da; @enderror"
                    >
                    @error('tags')
                        <div style="color: #be3144; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- DESCRIPCIÓ -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label for="description" style="font-size: 12px; font-weight: 600; color: #9a9a9a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Descripció</label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Explica la història darrere d'aquest plat..."
                        rows="4"
                        style="width: 100%; padding: 12px 16px; background-color: #f3f3f3; border: none; border-radius: 12px; font-size: 14px; color: #2d2d2d; transition: all 0.3s ease; font-family: inherit; resize: vertical; @error('description') background-color: #fff5f5; border: 1px solid #f8d7da; @enderror"
                    >{{ old('description', $recipe->description ?? '') }}</textarea>
                    @error('description')
                        <div style="color: #be3144; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- INGREDIENTS -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label for="ingredients" style="font-size: 12px; font-weight: 600; color: #9a9a9a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Ingredients</label>
                    <textarea
                        id="ingredients"
                        name="ingredients"
                        placeholder="Un ingredient per línia&#10;Ex: 320g d'arròs Carnaroli&#10;1.5L de brou de verdures"
                        rows="5"
                        style="width: 100%; padding: 12px 16px; background-color: #f3f3f3; border: none; border-radius: 12px; font-size: 14px; color: #2d2d2d; transition: all 0.3s ease; font-family: inherit; resize: vertical; @error('ingredients') background-color: #fff5f5; border: 1px solid #f8d7da; @enderror"
                    >{{ $ingredientsValue }}</textarea>
                    <p style="font-size: 12px; color: #7a7a7a; margin: 4px 0 0;">Escriu cada ingredient en una nova línia</p>
                    @error('ingredients')
                        <div style="color: #be3144; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- IMATGE -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label for="image" style="font-size: 12px; font-weight: 600; color: #9a9a9a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Imatge de la Recepta</label>
                    <div id="imageDropZone" style="width: 100%; min-height: 200px; border: 2px dashed #d0d0d0; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #fafafa; cursor: pointer; transition: all 0.2s ease; position: relative; overflow: hidden;">
                        @if($isEditing && !empty($recipe->image))
                            <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.72);"></div>
                        @endif
                        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" style="display: none;">
                        <div style="text-align: center; pointer-events: none; position: relative; z-index: 1;">
                            <div style="font-size: 48px; color: #999; margin-bottom: 12px;">
                                <i class="bi bi-image"></i>
                            </div>
                            <p id="imageUploadPrimaryText" style="margin: 0 0 6px; font-size: 14px; color: #2d2d2d; font-weight: 500;">
                                {{ $isEditing ? 'Fes clic per substituir la imatge actual' : 'Fes clic per pujar una imatge' }}
                            </p>
                            <p id="imageUploadSecondaryText" style="font-size: 12px; color: #7a7a7a;">PNG, JPG o JPEG fins a 2MB</p>
                        </div>
                    </div>
                    <div id="imageUploadStatus" aria-live="polite" style="display: none; align-items: center; gap: 8px; color: #198754; font-size: 12px; font-weight: 600; margin-top: 4px;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span id="imageUploadStatusText"></span>
                    </div>
                    @if($isEditing && !empty($recipe->image))
                        <p style="font-size: 12px; color: #7a7a7a; margin: 4px 0 0;">Si no selecciones cap fitxer nou, mantindrem la imatge actual.</p>
                    @endif
                    @error('image')
                        <div style="color: #be3144; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notes del xef visibles també durant la creació per alimentar el detall de la recepta -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label for="chef_notes" style="font-size: 12px; font-weight: 600; color: #9a9a9a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Notes del Xef (opcional)</label>
                    <textarea
                        id="chef_notes"
                        name="chef_notes"
                        placeholder="Afegeix un consell final, un truc de cocció o una recomanació de presentació..."
                        rows="2"
                        style="width: 100%; padding: 12px 16px; background-color: #f3f3f3; border: none; border-radius: 12px; font-size: 14px; color: #2d2d2d; transition: all 0.3s ease; font-family: inherit; resize: vertical; @error('chef_notes') background-color: #fff5f5; border: 1px solid #f8d7da; @enderror"
                    >{{ old('chef_notes', $recipe->chef_notes ?? '') }}</textarea>
                    @error('chef_notes')
                        <div style="color: #be3144; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- BOTONS D'ACCIÓ -->
                <div style="display: flex; align-items: center; gap: 16px; margin-top: 12px; padding-top: 20px; border-top: 1px solid #e8e8e8;">
                    <button
                        type="submit"
                        style="height: 44px; padding: 0 24px; background-color: #be3144; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;"
                    >
                        <i class="bi bi-check-lg"></i>
                        <span>{{ $submitLabel }}</span>
                    </button>
                    <a
                        href="{{ $backUrl }}"
                        style="height: 44px; padding: 0 24px; background-color: transparent; color: #7a7a7a; border: 1px solid #d0d0d0; border-radius: 12px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;"
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
                            style="height: 44px; padding: 0 24px; margin-left: auto; background-color: transparent; color: #be3144; border: 1px solid #be3144; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;"
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
