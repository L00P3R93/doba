<div>
    @if(session('success'))
        <div class="alert alert-info">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" enctype="multipart/form-data">
        <div class="subheading">Advertiser Name</div>
        <input type="text" wire:model="advertiser" placeholder="Enter advertiser name" class="form-control mb-2" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;" required>

        <div class="subheading">Ad Headline</div>
        <input type="text" wire:model="headline" placeholder="Enter headline" class="form-control mb-2" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;" required>

        <div class="subheading">CTA Text</div>
        <input type="text" wire:model="ctaText" placeholder="Enter CTA text" class="form-control mb-2" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;" required>

        <div class="subheading">CTA URL (Optional)</div>
        <input type="text" wire:model="ctaUrl" placeholder="Enter CTA URL" class="form-control mb-2" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;" required>

        <div class="subheading">Upload Audio File</div>
        <input type="file" wire:model="audioFile" name="audio_file" class="form-control mb-2" accept="audio/*" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;" required>
        @error('audioFile')
            <div class="text-danger small mb-2">{{ $message }}</div>
        @enderror

        <div class="subheading">Upload Banner Image (Optional)</div>
        <input type="file" wire:model="bannerImage" name="banner_image" class="form-control mb-2" accept="image/*" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;">
        @error('bannerImage')
            <div class="text-danger small mb-2">{{ $message }}</div>
        @enderror

        <div class="subheading">Price per Impression/Priority</div>
        <input type="text" wire:model="basePricePerImpression" class="form-control mb-2" value="1.00" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;" required>

        <div class="subheading">Target Audience</div>
        <select wire:model="level" class="form-control mb-2 select2" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;">
            <option value="general">General</option>
            <option value="county">County</option>
            <option value="subcounty">Sub County</option>
            <option value="ward">Ward</option>
        </select>

        @if($level !== 'general')
            <div class="subheading">Select County</div>
            <select wire:model="countyId" wire:change="updatedCountyId" class="form-control mb-2 select2" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;">
                <option value="">Select county</option>
                @foreach($this->counties as $county)
                    <option value="{{ $county->id }}">{{ $county->name }}</option>
                @endforeach
            </select>
        @endif

        @if($level === 'subcounty' || $level === 'ward')
            <div class="subheading">Select Subcounty</div>
            <select wire:model="subCountyId" wire:change="updatedSubCountyId" class="form-control mb-2 select2" :disabled="!$countyId" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;">
                <option value="">Select subcounty</option>
                @foreach($this->subCounties as $subCounty)
                    <option value="{{ $subCounty->id }}">{{ $subCounty->name }}</option>
                @endforeach
            </select>
        @endif

        @if($level === 'ward')
            <div class="subheading">Select Ward</div>
            <select wire:model="wardId" class="form-control mb-2 select2" :disabled="!$subCountyId" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;">
                <option value="">Select ward</option>
                @foreach($this->wards as $ward)
                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                @endforeach
            </select>
        @endif

        <div class="subheading">Budget (KES)</div>
        <input type="number" wire:model="budget" placeholder="Enter budget in KES" class="form-control mb-2" style="background: rgba(15, 45, 63, 0.5); border: 1px solid rgba(240, 229, 84, 0.3); color: #fff; border-radius: 6px;" required>

        <div class="mb-2" style="color: #cfe1ee;">
            Final Price: <b style="color: #f5c542;">KES {{ number_format($this->pricingPreview->finalPricePerImpression, 2) }}</b> |
            Max Impressions: <b style="color: #f5c542;">{{ number_format($this->pricingPreview->maxImpressions) }}</b>
        </div>

        <button class="btn btn-gold w-100" wire:loading.attr="disabled">
            <span wire:loading.remove>Request Audio Ad</span>
            <span wire:loading>Processing...</span>
        </button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'classic',
                dropdownCssClass: 'select2-dark-dropdown',
                containerCssClass: 'select2-dark-container'
            });
        });
    </script>

    <style>
        .select2-dark-dropdown .select2-results__option {
            color: #fff;
            background: #0f2d3f;
        }
        .select2-dark-dropdown .select2-results__option[aria-selected="true"] {
            background: #f5c542;
            color: #000;
        }
        .select2-dark-container .select2-selection {
            background: rgba(15, 45, 63, 0.5);
            border: 1px solid rgba(240, 229, 84, 0.3);
            color: #fff;
        }
        .select2-dark-dropdown {
            background: #0f2d3f;
            border: 1px solid rgba(240, 229, 84, 0.3);
        }
        .subheading {
            font-size: 0.85rem;
            color: #cfe1ee;
            margin-bottom: 4px;
        }
    </style>
</div>
