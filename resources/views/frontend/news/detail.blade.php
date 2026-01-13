@extends('layouts.main')

@section('title', 'Detail Berita')

@section('styles')
<style>
.article-container {
    max-width: 800px;
}

.article-header {
    margin-bottom: 24px;
}

.article-title {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.3;
    margin-bottom: 16px;
}

.article-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12px;
    color: #6b7280;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.article-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
}

.article-image {
    width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 24px 0;
}

.article-content {
    font-size: 16px;
    line-height: 1.8;
    color: #374151;
}

.article-content p {
    margin-bottom: 16px;
}

.comment-section {
    margin-top: 48px;
    padding-top: 32px;
    border-top: 2px solid #e5e7eb;
}

.comment-title {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 24px;
}

.comment-form {
    background: #f9fafb;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 32px;
}

.form-group {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.form-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #1E88E5;
    box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.btn-submit {
    background: #1E88E5;
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-submit:hover {
    background: #1976d2;
}

.comment-list {
    space-y: 20px;
}

.comment-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.comment-author {
    font-weight: 600;
    color: #1f2937;
    font-size: 14px;
}

.comment-date {
    font-size: 12px;
    color: #9ca3af;
}

.comment-body {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.6;
}

.sidebar {
    position: sticky;
    top: 80px;
}

.sidebar-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.sidebar-title {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 16px;
}

.related-news-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.related-news-item:last-child {
    border-bottom: none;
}

.related-news-img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
    flex-shrink: 0;
}

.related-news-title {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.related-news-date {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
}

.social-share {
    display: flex;
    gap: 8px;
}

.share-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    transition: all 0.2s;
}

.share-btn:hover {
    transform: scale(1.1);
}

.share-facebook { background: #1877f2; }
.share-twitter { background: #1da1f2; }
.share-whatsapp { background: #25d366; }
.share-link { background: #6b7280; }
</style>
@endsection

@section('content')

<div class="bg-white py-8">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- MAIN CONTENT -->
            <div class="lg:col-span-2">
                <article class="article-container">

                    <!-- Article Header -->
                    <div class="article-header">
                        <h1 class="article-title">
                            Furor Over Chinese Spy Balloon Leads to a Diplomatic Crisis
                        </h1>

                        <div class="article-meta">
                            <span class="article-meta-item">
                                <i class="bi bi-person-circle"></i>
                                InfoBatang
                            </span>
                            <span class="article-meta-item">
                                <i class="bi bi-calendar3"></i>
                                3 Feb 2023
                            </span>
                            <span class="article-meta-item">
                                <i class="bi bi-clock"></i>
                                5 min read
                            </span>
                            <span class="article-meta-item">
                                <i class="bi bi-eye"></i>
                                1,234 views
                            </span>
                        </div>
                    </div>

                    <!-- Article Image -->
                    <img src="https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=800&h=450&fit=crop"
                         alt="Article Image"
                         class="article-image">

                    <!-- Article Content -->
                    <div class="article-content">
                        <p>
                            <strong>WASHINGTON</strong> — Secretary of State Antony J. Blinken on Friday canceled a planned trip to Beijing after a Chinese spy balloon was sighted drifting above the northwestern United States, throwing a diplomatic crisis atop long-simmering tensions over trade and Taiwan.
                        </p>

                        <p>
                            The decision came just hours after the Pentagon publicly acknowledged the presence of the balloon and said it was being tracked as it moved at high altitude across the country. Defense officials said they decided against shooting it down for fear of hurting people on the ground.
                        </p>

                        <p>
                            Mr. Blinken had been scheduled to leave Washington on Friday evening for what would have been a high-stakes visit aimed at stabilizing ties between the world's two largest economies after a period of mounting friction. The trip would have been the first by a U.S. secretary of state to China since 2018.
                        </p>

                        <p>
                            But with Republicans and some Democrats demanding a forceful response to what they described as a brazen act of espionage, Mr. Blinken said he told his Chinese counterpart, Wang Yi, that visiting Beijing "would not be constructive" under the circumstances.
                        </p>

                        <p>
                            "In my call with Director Wang Yi, I made clear that the presence of this surveillance balloon in U.S. airspace is a clear violation of U.S. sovereignty and international law, that it's an irresponsible act, and that the P.R.C.'s decision to take this action on the eve of my planned visit is detrimental to the substantive discussions that we were prepared to have," Mr. Blinken said in a statement, referring to the People's Republic of China.
                        </p>

                        <p>
                            The State Department said both sides had agreed that Mr. Blinken would visit China "at the earliest opportunity when conditions allow." But the episode highlighted the fragility of the relationship between Washington and Beijing and underscored how quickly events can upend efforts at diplomacy.
                        </p>

                        <p>
                            China's foreign ministry expressed regret over the incident but insisted the balloon was a civilian airship used mainly for meteorological research that had been blown off course. The ministry said China would "continue communicating with the U.S. side and properly handle this unexpected situation caused by force majeure."
                        </p>
                    </div>

                    <!-- Social Share -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Bagikan artikel ini:</p>
                        <div class="social-share">
                            <a href="#" class="share-btn share-facebook" title="Share to Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="share-btn share-twitter" title="Share to Twitter">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="share-btn share-whatsapp" title="Share to WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="#" class="share-btn share-link" title="Copy Link">
                                <i class="bi bi-link-45deg"></i>
                            </a>
                        </div>
                    </div>
