<div class="baze-legal" style="color: var(--color-text-primary); font-family: var(--font-sans);">

    {{-- ============================================================
         HERO
         ============================================================ --}}
    <section class="baze-legal-hero">
        <div class="baze-wrap">
            <div class="baze-eyebrow" style="justify-content:center;">LEGAL &middot; DOBAPLAY</div>

            <h1 class="baze-display baze-legal-h1">Terms of Service</h1>
            <p class="baze-legal-subhead">The rules of the record.</p>

            <p class="baze-legal-updated">Last updated: {{ $effectiveDate }}</p>

            <nav class="baze-legal-switch" aria-label="Legal pages">
                <a href="{{ route('terms') }}" aria-current="page">Terms of Service</a>
                <a href="{{ route('privacy') }}">Privacy Policy</a>
            </nav>
        </div>
    </section>

    {{-- ============================================================
         MOBILE TABLE OF CONTENTS — native <details>, no JS required
         ============================================================ --}}
    <div class="baze-wrap baze-legal-toc-mobile">
        <details>
            <summary>On this page <span aria-hidden="true">&darr;</span></summary>
            <ul>
                @foreach ($sections as $section)
                    <li><a href="#{{ $section['id'] }}">{{ sprintf('%02d', $loop->iteration) }}&nbsp; {{ $section['title'] }}</a></li>
                @endforeach
            </ul>
        </details>
    </div>

    {{-- ============================================================
         CONTENT + DESKTOP TOC
         ============================================================ --}}
    <div class="baze-section">
        <div class="baze-wrap baze-legal-layout">
            <aside class="baze-legal-toc" aria-label="Table of contents">
                <div class="baze-legal-toc-inner">
                    <p class="baze-legal-toc-label">Contents</p>
                    <ol>
                        @foreach ($sections as $section)
                            <li>
                                <a href="#{{ $section['id'] }}">
                                    <span class="baze-legal-toc-num">{{ sprintf('%02d', $loop->iteration) }}</span>
                                    {{ $section['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </aside>

            <article class="baze-legal-content" aria-label="Terms of Service content">
                @foreach ($sections as $section)
                    <section id="{{ $section['id'] }}" class="baze-legal-section">
                        <span class="baze-legal-num" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span>
                        <h2>{{ $section['title'] }}</h2>

                        @foreach ($section['paragraphs'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach

                        @if (! empty($section['definitions']))
                            <dl class="baze-legal-definitions">
                                @foreach ($section['definitions'] as $term => $definition)
                                    <div>
                                        <dt>&ldquo;{{ $term }}&rdquo;</dt>
                                        <dd>{{ $definition }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        @endif

                        @if (! empty($section['notice']))
                            <div class="baze-legal-notice" role="note">
                                <strong>Important</strong>
                                <p>{{ $section['notice'] }}</p>
                            </div>
                        @endif
                    </section>
                @endforeach

                <div class="baze-legal-contact">
                    <div class="baze-eyebrow" style="color: var(--color-frequency);">Questions?</div>
                    <h3 class="baze-display">Questions about these Terms?</h3>
                    <p>
                        If anything here is unclear, reach out and we'll walk you through it.
                    </p>
                    <a href="mailto:{{ $contactEmail }}" class="btn btn-outline">{{ $contactEmail }}</a>
                </div>
            </article>
        </div>
    </div>
</div>
