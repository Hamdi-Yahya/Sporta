{{-- ─── Card Lapangan (§3.7) ──────────────────────────────────────
     Props: $name, $sport, $type, $location, $price, $rating,
            $reviewCount, $image, $href
─────────────────────────────────────────────────────────────── --}}
@props([
    'name'        => 'Lapangan Futsal Inti',
    'sport'       => 'Futsal',
    'type'        => 'Indoor',
    'location'    => 'Pekalongan Barat',
    'price'       => '90.000',
    'rating'      => 4.7,
    'reviewCount' => 23,
    'image'       => null,
    'href'        => '#',
])

<a href="{{ $href }}" style="text-decoration:none;display:block;" class="field-card-wrapper">
    <div class="card" style="transition:box-shadow 0.2s,transform 0.2s;cursor:pointer;"
         onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)';this.style.transform='translateY(-2px)'"
         onmouseout="this.style.boxShadow='';this.style.transform=''">

        {{-- Foto Lapangan --}}
        <div style="position:relative;height:172px;overflow:hidden;background:#e2e8f0;">
            @if($image)
                <img src="{{ $image }}" alt="{{ $name }}"
                     style="width:100%;height:100%;object-fit:cover;">
            @else
                {{-- Placeholder jika tidak ada foto --}}
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#e2e8f0,#cbd5e1);">
                    <i data-lucide="image" style="width:36px;height:36px;color:#94A3B8;"></i>
                </div>
            @endif

            {{-- Sport badge --}}
            <div style="position:absolute;top:10px;left:10px;background:rgba(15,46,28,0.85);
                        color:#fff;font-size:0.6875rem;font-weight:600;padding:3px 9px;border-radius:4px;">
                {{ $sport }}
            </div>
        </div>

        {{-- Info --}}
        <div style="padding:14px 16px;">
            <h3 style="font-family:'Poppins',sans-serif;font-size:0.9375rem;font-weight:700;
                       color:#1E293B;margin:0 0 4px;line-height:1.3;
                       white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ $name }}
            </h3>

            <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                <i data-lucide="map-pin" style="width:13px;height:13px;color:#94A3B8;flex-shrink:0;"></i>
                <span style="font-size:0.8rem;color:#64748B;">{{ $location }}</span>
                <span style="color:#CBD5E1;font-size:0.75rem;">•</span>
                <span style="font-size:0.8rem;color:#64748B;">{{ $type }}</span>
            </div>

            {{-- Rating --}}
            <div style="display:flex;align-items:center;gap:5px;margin-bottom:12px;">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($rating))
                        <i data-lucide="star" style="width:13px;height:13px;color:#F59E0B;fill:#F59E0B;"></i>
                    @elseif($i - $rating < 1 && $i - $rating > 0)
                        <i data-lucide="star-half" style="width:13px;height:13px;color:#F59E0B;fill:#F59E0B;"></i>
                    @else
                        <i data-lucide="star" style="width:13px;height:13px;color:#CBD5E1;"></i>
                    @endif
                @endfor
                <span style="font-size:0.8rem;font-weight:600;color:#1E293B;">{{ number_format($rating, 1) }}</span>
                <span style="font-size:0.8rem;color:#94A3B8;">({{ $reviewCount }} ulasan)</span>
            </div>

            {{-- Price --}}
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <span style="font-size:0.75rem;color:#94A3B8;">mulai dari</span>
                    <div style="font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;color:#166534;">
                        Rp {{ $price }}<span style="font-size:0.75rem;font-weight:400;color:#64748B;">/jam</span>
                    </div>
                </div>
                <div style="width:32px;height:32px;border-radius:50%;background:#16A34A;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="arrow-right" style="width:15px;height:15px;color:#fff;"></i>
                </div>
            </div>
        </div>
    </div>
</a>
