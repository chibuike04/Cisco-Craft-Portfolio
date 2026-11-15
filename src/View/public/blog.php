<?php

/** @var array $blogs */
/** @var array $categories */
/** @var array $pagination */

$escape = static fn(mixed $value): string => htmlspecialchars(
  (string) ($value ?? ''),
  ENT_QUOTES,
  'UTF-8'
);

$formatBlogDate = static function (?string $date): string {
  if (!$date) {
    return 'Not dated';
  }

  try {
    return (new DateTime($date))->format('M d, Y');
  } catch (Throwable) {
    return 'Not dated';
  }
};

$blogs = is_array($blogs ?? null) ? $blogs : [];
$categories = is_array($categories ?? null) ? $categories : [];
$pagination = is_array($pagination ?? null) ? $pagination : [];
$currentPage = max(1, (int) ($pagination['current_page'] ?? 1));
$totalPages = max(1, (int) ($pagination['total_pages'] ?? 1));
$blogBaseUrl = url('blog');
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#0d0806" />
  <meta
    name="description"
    content="Thoughts, tutorials, and insights on web development, technology, design, and productivity." />
  <title>Blog | CiscoCraft</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap"
    rel="stylesheet" />

  <style>
    :root {
      --bg-dark: #0d0806;
      --bg-soft: #160d09;
      --card-bg: linear-gradient(155deg,
          rgba(36, 22, 17, 0.82),
          rgba(22, 14, 10, 0.88));
      --card-border: rgba(245, 158, 11, 0.16);
      --card-hover-border: rgba(245, 158, 11, 0.48);
      --primary: #e07a3f;
      --gold: #f59e0b;
      --gold-bright: #ffb21a;
      --text-main: #fcf9f6;
      --text-muted: #a89c94;
      --text-dim: #71655e;
      --gradient: linear-gradient(135deg,
          #f59e0b 0%,
          #e07a3f 55%,
          #c65f24 100%);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scroll-behavior: smooth;
      background: var(--bg-dark);
      overflow-x: clip;
    }

    body {
      min-height: 100vh;
      width: 100%;
      color: var(--text-main);
      font-family: "DM Sans", sans-serif;
      background:
        radial-gradient(ellipse at 8% 5%,
          rgba(224, 122, 63, 0.14),
          transparent 32%),
        radial-gradient(ellipse at 94% 16%,
          rgba(198, 95, 36, 0.12),
          transparent 30%),
        radial-gradient(ellipse at 50% 58%,
          rgba(224, 122, 63, 0.045),
          transparent 48%),
        linear-gradient(rgba(224, 122, 63, 0.018) 1px, transparent 1px),
        linear-gradient(90deg,
          rgba(224, 122, 63, 0.018) 1px,
          transparent 1px),
        #0d0806;
      background-size:
        auto,
        auto,
        auto,
        48px 48px,
        48px 48px,
        auto;
      overflow-x: clip;
    }

    button,
    input {
      font: inherit;
    }

    a {
      color: inherit;
    }

    img {
      display: block;
      max-width: 100%;
    }

    .page-shell {
      position: relative;
      isolation: isolate;
      min-height: 100vh;
      overflow: hidden;
    }

    .ambient {
      position: absolute;
      z-index: -1;
      pointer-events: none;
      border-radius: 50%;
      filter: blur(75px);
      opacity: 0.72;
    }

    .ambient-left {
      width: 360px;
      height: 360px;
      top: -190px;
      left: -150px;
      background: rgba(224, 122, 63, 0.28);
    }

    .ambient-right {
      width: 420px;
      height: 420px;
      top: -250px;
      right: -190px;
      background: rgba(198, 95, 36, 0.24);
    }

    .ambient-bottom {
      width: 500px;
      height: 300px;
      bottom: 0;
      left: 30%;
      background: rgba(245, 158, 11, 0.055);
    }

    .container {
      width: min(100% - 48px, 1160px);
      margin-inline: auto;
    }

    .site-header {
      height: 76px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(224, 122, 63, 0.1);
      position: relative;
      z-index: 5;
    }

    .brand {
      display: inline-flex;
      align-items: center;
      gap: 9px;
      text-decoration: none;
      font-size: 19px;
      font-weight: 800;
      letter-spacing: -0.5px;
    }

    .brand-mark {
      color: var(--gold);
      font-family: "Fira Code", monospace;
    }

    .brand span:last-child {
      color: #fff;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 30px;
      list-style: none;
    }

    .nav-links a {
      text-decoration: none;
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 600;
      transition: color 0.2s;
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: #fff;
    }

    .nav-links a.active {
      position: relative;
    }

    .nav-links a.active::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: -10px;
      height: 2px;
      border-radius: 2px;
      background: var(--gradient);
    }

    .header-cta {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 19px;
      border-radius: 999px;
      color: #170d07;
      text-decoration: none;
      font-size: 12px;
      font-weight: 800;
      background: var(--gradient);
      box-shadow: 0 5px 20px rgba(224, 122, 63, 0.22);
      transition:
        transform 0.2s,
        box-shadow 0.2s;
    }

    .header-cta:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(224, 122, 63, 0.35);
    }

    .blog-intro {
      text-align: center;
      padding: 52px 0 28px;
      position: relative;
    }

    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 6px 13px;
      color: #fbbf24;
      background: rgba(245, 158, 11, 0.07);
      border: 1px solid rgba(245, 158, 11, 0.3);
      border-radius: 999px;
      text-transform: uppercase;
      letter-spacing: 1.25px;
      font:
        700 9px "Fira Code",
        monospace;
      margin-bottom: 12px;
    }

    .eyebrow::before {
      content: "";
      width: 5px;
      height: 5px;
      border-radius: 50%;
      background: var(--gold);
      box-shadow: 0 0 9px var(--gold);
    }

    h1 {
      font-size: clamp(34px, 5vw, 48px);
      line-height: 1.05;
      letter-spacing: -1.8px;
      font-weight: 800;
    }

    h1 span {
      color: var(--gold);
      text-shadow: 0 0 28px rgba(245, 158, 11, 0.18);
    }

    .intro-copy {
      max-width: 560px;
      margin: 10px auto 0;
      color: var(--text-muted);
      font-size: 13px;
      line-height: 1.65;
    }

    .filter-bar {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 9px;
      padding: 0 0 25px;
    }

    .filter-btn {
      border: 1px solid rgba(224, 122, 63, 0.22);
      color: #d7c9bf;
      background: rgba(28, 17, 12, 0.65);
      padding: 9px 17px;
      border-radius: 999px;
      cursor: pointer;
      font-size: 11px;
      font-weight: 700;
      transition:
        color 0.2s,
        border-color 0.2s,
        background 0.2s,
        box-shadow 0.2s;
    }

    .filter-btn:hover {
      border-color: rgba(245, 158, 11, 0.55);
      color: #fff;
    }

    .filter-btn.active {
      color: #1a0d06;
      border-color: transparent;
      background: var(--gradient);
      box-shadow: 0 4px 17px rgba(245, 158, 11, 0.25);
    }

    .posts-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 18px;
    }

    .post-card {
      min-width: 0;
      overflow: hidden;
      border-radius: 13px;
      border: 1px solid var(--card-border);
      background: var(--card-bg);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.22);
      transition:
        transform 0.25s,
        border-color 0.25s,
        box-shadow 0.25s;
    }

    .post-card:hover {
      transform: translateY(-5px);
      border-color: var(--card-hover-border);
      box-shadow:
        0 18px 38px rgba(0, 0, 0, 0.36),
        0 0 22px rgba(245, 158, 11, 0.055);
    }

    .post-card[hidden] {
      display: none;
    }

    .post-image-wrap {
      height: 154px;
      position: relative;
      overflow: hidden;
      background: #21140e;
    }

    .post-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s;
    }

    .post-card:hover .post-image {
      transform: scale(1.055);
    }

    .post-image-wrap::after {
      content: "";
      position: absolute;
      inset: 45% 0 0;
      background: linear-gradient(transparent, rgba(13, 8, 6, 0.28));
      pointer-events: none;
    }

    .category-tag {
      position: absolute;
      z-index: 1;
      top: 10px;
      right: 10px;
      padding: 5px 9px;
      border-radius: 999px;
      border: 1px solid rgba(245, 158, 11, 0.48);
      background: rgba(20, 11, 7, 0.88);
      color: #ffb21a;
      font-size: 9px;
      font-weight: 800;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.25);
    }

    .post-content {
      padding: 12px 14px 14px;
    }

    .post-meta {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 10px;
      color: #9b8d83;
      font-size: 9px;
      margin-bottom: 7px;
    }

    .post-meta span {
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .meta-icon {
      color: var(--gold);
      font-size: 10px;
    }

    .post-title {
      font-size: 15px;
      line-height: 1.28;
      font-weight: 800;
      letter-spacing: -0.25px;
      margin-bottom: 7px;
    }

    .post-title a {
      text-decoration: none;
      transition: color 0.2s;
    }

    .post-title a:hover {
      color: #ffb21a;
    }

    .post-excerpt {
      color: var(--text-muted);
      font-size: 11px;
      line-height: 1.55;
      min-height: 34px;
    }

    .read-more {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      margin-top: 9px;
      color: #ffad18;
      text-decoration: none;
      font-size: 10px;
      font-weight: 800;
    }

    .read-more span {
      transition: transform 0.2s;
    }

    .read-more:hover span {
      transform: translateX(4px);
    }

    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 7px;
      padding: 23px 0 8px;
    }

    .page-btn {
      width: 29px;
      height: 29px;
      display: inline-grid;
      place-items: center;
      border-radius: 50%;
      border: 1px solid rgba(224, 122, 63, 0.26);
      background: rgba(28, 17, 12, 0.7);
      color: #d8c9bf;
      font-size: 10px;
      font-weight: 700;
      cursor: pointer;
      transition: 0.2s;
    }

    .page-btn:hover {
      border-color: var(--gold);
      color: #fff;
    }

    .page-btn.active {
      background: var(--gradient);
      color: #1a0d06;
      border-color: transparent;
      box-shadow: 0 3px 12px rgba(245, 158, 11, 0.25);
    }

    .page-btn:disabled {
      opacity: 0.35;
      cursor: not-allowed;
    }

    .newsletter {
      margin: 0 auto 42px;
      max-width: 1060px;
      min-height: 82px;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      gap: 18px;
      padding: 17px 28px;
      border: 1px solid rgba(245, 158, 11, 0.25);
      border-radius: 13px;
      background: linear-gradient(100deg,
          rgba(66, 32, 14, 0.88),
          rgba(28, 16, 11, 0.94) 42%,
          rgba(39, 20, 11, 0.9));
      box-shadow:
        inset 0 1px 0 rgba(255, 190, 80, 0.08),
        0 15px 35px rgba(0, 0, 0, 0.25);
    }

    .newsletter::before,
    .newsletter::after {
      content: "";
      position: absolute;
      pointer-events: none;
      width: 110px;
      height: 160px;
      border: 5px solid rgba(245, 126, 17, 0.85);
      border-radius: 50%;
      filter: drop-shadow(0 0 9px rgba(245, 126, 11, 0.35));
    }

    .newsletter::before {
      left: -93px;
      top: -43px;
    }

    .newsletter::after {
      right: -93px;
      bottom: -90px;
    }

    .newsletter-icon {
      flex: 0 0 43px;
      width: 43px;
      height: 43px;
      display: grid;
      place-items: center;
      border-radius: 50%;
      color: #ffb21a;
      font-size: 20px;
      border: 1px solid rgba(245, 158, 11, 0.35);
      background: rgba(245, 158, 11, 0.1);
      box-shadow: 0 0 18px rgba(245, 158, 11, 0.15);
    }

    .newsletter-copy {
      flex: 1;
      min-width: 0;
    }

    .newsletter-copy h2 {
      font-size: 14px;
      margin-bottom: 3px;
      font-weight: 800;
    }

    .newsletter-copy p {
      color: var(--text-muted);
      font-size: 10px;
      line-height: 1.5;
    }

    .newsletter-form {
      display: flex;
      gap: 7px;
      width: min(100%, 365px);
      position: relative;
      z-index: 1;
    }

    .newsletter-form input {
      flex: 1;
      min-width: 0;
      height: 36px;
      border-radius: 9px;
      outline: none;
      border: 1px solid rgba(224, 122, 63, 0.22);
      background: rgba(13, 8, 6, 0.62);
      color: #fff;
      padding: 0 12px;
      font-size: 10px;
    }

    .newsletter-form input::placeholder {
      color: #8c7b70;
    }

    .newsletter-form input:focus {
      border-color: var(--gold);
    }

    .newsletter-form button {
      flex: 0 0 auto;
      height: 36px;
      border: 0;
      border-radius: 999px;
      padding: 0 19px;
      background: var(--gradient);
      color: #1b0e07;
      font-size: 10px;
      font-weight: 800;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(245, 158, 11, 0.2);
      transition: transform 0.2s;
    }

    .newsletter-form button:hover {
      transform: translateY(-2px);
    }

    .newsletter-message {
      display: none;
      color: #86efac;
      font-size: 10px;
      margin-top: 5px;
    }

    .newsletter-message[hidden] {
      display: none;
    }

    .newsletter-message.success {
      color: #22c55e;
    }

    .newsletter-message.error {
      color: #ef4444;
    }

    .newsletter-submit:disabled,
    .newsletter-form button:disabled {
      opacity: 0.65;
      cursor: not-allowed;
    }

    .site-footer {
      border-top: 1px solid rgba(224, 122, 63, 0.1);
      padding: 20px 0 25px;
      color: var(--text-dim);
      font-size: 11px;
    }

    .footer-inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 15px;
    }

    .footer-links {
      display: flex;
      gap: 18px;
      flex-wrap: wrap;
    }

    .footer-links a {
      color: var(--text-muted);
      text-decoration: none;
    }

    .footer-links a:hover {
      color: #fff;
    }

    /* CONTACT-STYLE FOOTER — preserves the existing page background */
    .site-footer {
      position: relative;
      padding: 58px 0 26px;
      border-top: 1px solid rgba(224, 122, 63, 0.1);
      background: transparent;
    }

    .footer-main {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1.25fr) minmax(0, 0.9fr);
      gap: 48px;
      align-items: start;
      padding: 0 0 52px;
    }

    .footer-cta {
      min-width: 0;
    }

    .footer-eyebrow {
      display: inline-flex;
      align-items: center;
      padding: 7px 16px;
      margin-bottom: 22px;
      border: 1px solid rgba(245, 158, 11, 0.38);
      border-radius: 999px;
      background: rgba(245, 158, 11, 0.07);
      color: #ffb21a;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 1.1px;
      text-transform: uppercase;
    }

    .footer-cta h2 {
      color: #fff;
      font-size: clamp(28px, 3vw, 38px);
      line-height: 1.18;
      letter-spacing: -1px;
      font-weight: 800;
      margin-bottom: 19px;
    }

    .footer-cta p {
      max-width: 410px;
      color: var(--text-muted);
      font-size: 15px;
      line-height: 1.8;
      margin-bottom: 28px;
    }

    .footer-contact-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
      min-height: 46px;
      padding: 0 27px;
      border-radius: 999px;
      background: var(--gradient);
      color: #fff;
      text-decoration: none;
      font-size: 14px;
      font-weight: 800;
      box-shadow: 0 7px 22px rgba(224, 122, 63, 0.28);
      transition:
        transform 0.2s,
        box-shadow 0.2s;
    }

    .footer-contact-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(224, 122, 63, 0.4);
    }

    .footer-contact-btn span {
      font-size: 18px;
      line-height: 1;
    }

    .footer-testimonial {
      min-width: 0;
      min-height: 290px;
      display: flex;
      flex-direction: column;
      padding: 27px 29px;
      border: 1px solid rgba(245, 158, 11, 0.2);
      border-radius: 22px;
      background: linear-gradient(155deg,
          rgba(36, 22, 17, 0.78),
          rgba(22, 14, 10, 0.82));
      box-shadow: inset 0 1px 0 rgba(245, 158, 11, 0.06);
    }

    .footer-testimonial:hover {
      box-shadow:
        inset 0 1px 0 0 rgba(245, 158, 11, 0.35),
        0 20px 45px rgba(0, 0, 0, 0.5),
        0 0 20px rgba(245, 158, 11, 0.12);
    }

    .quote-mark {
      color: var(--primary);
      font-family: Georgia, serif;
      font-size: 36px;
      font-weight: 800;
      line-height: 0.9;
      margin-bottom: 25px;
    }

    .footer-testimonial blockquote {
      color: #e8ddd5;
      font-size: 16px;
      line-height: 1.7;
      font-weight: 600;
      font-style: italic;
      margin-bottom: 28px;
    }

    .testimonial-person {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-top: auto;
    }

    .testimonial-avatar {
      width: 48px;
      height: 48px;
      flex: 0 0 48px;
      position: relative;
      overflow: hidden;
      border-radius: 50%;
      border: 2px solid rgba(224, 122, 63, 0.55);
      background: rgba(224, 122, 63, 0.12);
    }

    .testimonial-avatar::before {
      content: "";
      position: absolute;
      width: 15px;
      height: 15px;
      left: 14px;
      top: 8px;
      border-radius: 50%;
      background: #f4c5a8;
    }

    .testimonial-avatar span {
      position: absolute;
      width: 30px;
      height: 17px;
      left: 7px;
      bottom: 3px;
      border-radius: 18px 18px 5px 5px;
      background: #d9773d;
    }

    .testimonial-person strong {
      display: block;
      color: #fff;
      font-size: 14px;
      margin-bottom: 2px;
    }

    .testimonial-person div:last-child span {
      color: #9f9188;
      font-size: 13px;
    }

    .footer-social {
      min-width: 0;
      padding-top: 2px;
    }

    .footer-social h3 {
      margin-bottom: 21px;
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 1.4px;
      text-transform: uppercase;
    }

    .social-links {
      display: flex;
      flex-wrap: wrap;
      gap: 13px;
      margin-bottom: 27px;
    }

    .social-links a {
      width: 46px;
      height: 46px;
      display: grid;
      place-items: center;
      border: 1px solid rgba(245, 158, 11, 0.2);
      border-radius: 13px;
      background: rgba(28, 18, 14, 0.55);
      color: #a89c94;
      transition: 0.2s;
    }

    .social-links a:hover {
      color: #fff;
      border-color: var(--primary);
      background: rgba(224, 122, 63, 0.12);
      transform: translateY(-2px);
    }

    .social-links svg {
      width: 19px;
      height: 19px;
      fill: currentColor;
    }

    .footer-contact-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .footer-contact-list a {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
      color: var(--text-muted);
      text-decoration: none;
      font-size: 14px;
      overflow-wrap: anywhere;
      transition: color 0.2s;
    }

    .footer-contact-list a:hover {
      color: #fff;
    }

    .footer-contact-list a span {
      color: var(--primary);
      font-size: 20px;
      line-height: 1;
    }

    .footer-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 18px;
      padding-top: 24px;
      border-top: 1px solid rgba(224, 122, 63, 0.12);
      color: var(--text-dim);
      font-size: 13px;
    }

    .footer-heart {
      color: #ff3b43;
      font-size: 17px;
      padding: 0 3px;
    }

    @media (max-width: 900px) {
      .footer-main {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 32px;
      }

      .footer-social {
        grid-column: 1 / -1;
      }

      .footer-testimonial {
        min-height: 260px;
      }
    }

    @media (max-width: 640px) {
      .site-footer {
        padding: 48px 0 22px;
      }

      .footer-main {
        grid-template-columns: minmax(0, 1fr);
        gap: 30px;
        padding-bottom: 34px;
      }

      .footer-cta h2 {
        font-size: 32px;
      }

      .footer-cta p {
        font-size: 14px;
        margin-bottom: 22px;
      }

      .footer-testimonial {
        min-height: 0;
        padding: 23px 21px;
      }

      .footer-testimonial blockquote {
        font-size: 14px;
        margin-bottom: 25px;
      }

      .footer-social {
        grid-column: auto;
      }

      .social-links {
        gap: 10px;
        margin-bottom: 22px;
      }

      .social-links a {
        width: 43px;
        height: 43px;
      }

      .footer-bottom {
        flex-direction: column;
        align-items: flex-start;
        gap: 9px;
        font-size: 12px;
      }

      .desktop-break {
        display: none;
      }
    }

    @media (max-width: 900px) {
      .posts-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .post-image-wrap {
        height: 175px;
      }

      .nav-links {
        gap: 18px;
      }
    }

    @media (max-width: 640px) {
      .container {
        width: min(100% - 36px, 560px);
      }

      .site-header {
        height: 68px;
      }

      .nav-links {
        display: none;
      }

      .brand {
        font-size: 17px;
      }

      .header-cta {
        padding: 9px 14px;
        font-size: 11px;
      }

      .blog-intro {
        padding: 43px 0 24px;
      }

      .intro-copy {
        font-size: 12px;
        max-width: 420px;
      }

      .filter-bar {
        gap: 7px;
        padding-bottom: 22px;
      }

      .filter-btn {
        padding: 8px 12px;
        font-size: 10px;
      }

      .posts-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 16px;
      }

      .post-image-wrap {
        height: clamp(170px, 52vw, 245px);
      }

      .post-content {
        padding: 14px 15px 16px;
      }

      .post-title {
        font-size: 16px;
      }

      .post-excerpt {
        font-size: 12px;
        min-height: 0;
      }

      .post-meta {
        font-size: 10px;
      }

      .read-more {
        font-size: 11px;
      }

      .pagination {
        padding: 22px 0 28px;
      }

      .newsletter {
        flex-wrap: wrap;
        gap: 12px;
        padding: 19px 17px;
        margin-bottom: 30px;
      }

      .newsletter-icon {
        width: 39px;
        height: 39px;
        flex-basis: 39px;
      }

      .newsletter-copy {
        flex: 1 1 calc(100% - 55px);
      }

      .newsletter-copy h2 {
        font-size: 14px;
      }

      .newsletter-copy p {
        font-size: 11px;
      }

      .newsletter-form {
        width: 100%;
      }

      .newsletter-form input {
        height: 40px;
        font-size: 11px;
      }

      .newsletter-form button {
        height: 40px;
      }

      .footer-inner {
        flex-direction: column;
        align-items: flex-start;
      }
    }

    @media (max-width: 380px) {
      .container {
        width: calc(100% - 28px);
      }

      .filter-btn {
        padding: 8px 10px;
      }

      .newsletter-form {
        flex-direction: column;
      }

      .newsletter-form button {
        width: 100%;
      }
    }

    @media (prefers-reduced-motion: reduce) {

      *,
      *::before,
      *::after {
        scroll-behavior: auto !important;
        transition-duration: 0.01ms !important;
        animation-duration: 0.01ms !important;
      }
    }
  </style>
</head>

<body>

  <div class="page-shell">
    <div class="ambient ambient-left"></div>
    <div class="ambient ambient-right"></div>
    <div class="ambient ambient-bottom"></div>

    <header class="site-header container">
      <a class="brand" href="<?= e(url('/')) ?>" aria-label="CiscoCraft home">
        <span class="brand-mark">&lt;/&gt;</span><span>CiscoCraft</span>
      </a>
      <nav aria-label="Main navigation">
        <ul class="nav-links">
          <li><a href="<?= e(url('/#home')) ?>" class="nav-link active">Home</a></li>
          <li><a href="<?= e(url('/#about')) ?>" class="nav-link">About</a></li>
          <li><a href="<?= e(url('/#skills')) ?>" class="nav-link">Skills</a></li>
          <li><a href="<?= e(url('/projects')) ?>" class="nav-link">Projects</a></li>
          <li><a href="<?= e(url('/blog')) ?>" class="nav-link">Blog</a></li>
          <li><a href="<?= e(url('/contact')) ?>" class="nav-link">Contact</a></li>
        </ul>
      </nav>
      <a class="header-cta" href="<?= e(url('/contact')) ?>">
        Hire Me <span aria-hidden="true">↗</span></a>
    </header>

    <main>
      <section class="blog-intro container">
        <div class="eyebrow">Insights &amp; Updates</div>
        <h1>Our <span>Blog</span></h1>
        <p class="intro-copy">
          Thoughts, tutorials, and insights on web development, technology,
          and more.<br />Stay updated with the latest from CodeCraft.
        </p>
      </section>

      <section class="container" aria-label="Blog posts">

        <!-- Dynamic Category Filters -->
        <div class="filter-bar" role="group" aria-label="Filter blog posts">

          <button
            class="filter-btn active"
            type="button"
            data-filter="all"
            aria-pressed="true">
            All Posts
          </button>

          <?php foreach ($categories as $category): ?>
            <button
              class="filter-btn"
              type="button"
              data-filter="<?= htmlspecialchars($category['slug'], ENT_QUOTES, 'UTF-8') ?>"
              aria-pressed="false">
              <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
            </button>
          <?php endforeach; ?>

        </div>

        <!-- Dynamic Blog Posts -->
        <div class="posts-grid" id="postsGrid">

          <?php if (!empty($blogs)): ?>

            <?php foreach ($blogs as $blog): ?>

              <?php
              $slug = (string) ($blog['slug'] ?? '');
              $categorySlug = (string) ($blog['category_slug'] ?? '');
              $categoryName = (string) ($blog['category_name'] ?? 'Uncategorized');

              $title = (string) ($blog['title'] ?? '');
              $excerpt = (string) ($blog['excerpt'] ?? '');

              $publishedDate = !empty($blog['published_at'])
                ? date('M d, Y', strtotime($blog['published_at']))
                : '';

              $readingMinutes = (int) ($blog['reading_minutes'] ?? 1);

              // Temporary images until the media table is populated.
              // Use the uploaded featured image when available.
              // Otherwise, fall back to the category image.
              $defaultImages = [
                'development' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=85',
                'tech-guide' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=900&q=85',
                'web-design' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=900&q=85',
                'career' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=900&q=85',
                'productivity' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=85',
              ];

              $uploadedImage = trim((string) ($blog['featured_image_url'] ?? ''));

              $image = $uploadedImage !== ''
                ? $uploadedImage
                : ($defaultImages[$categorySlug]
                  ?? 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=85');

              $articleUrl = url('/blog/' . rawurlencode($slug));
              ?>

              <article
                class="post-card"
                data-category="<?= htmlspecialchars($categorySlug, ENT_QUOTES, 'UTF-8') ?>">

                <div class="post-image-wrap">

                  <img
                    class="post-image"
                    src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                    loading="lazy" />

                  <span class="category-tag">
                    <?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') ?>
                  </span>

                </div>

                <div class="post-content">

                  <div class="post-meta">

                    <span>
                      <b class="meta-icon">▣</b>
                      <?= htmlspecialchars($publishedDate, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <span>
                      <b class="meta-icon">◷</b>
                      <?= $readingMinutes ?> min read
                    </span>

                  </div>

                  <h2 class="post-title">
                    <a href="<?= htmlspecialchars($articleUrl, ENT_QUOTES, 'UTF-8') ?>">
                      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                  </h2>

                  <p class="post-excerpt">
                    <?= htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8') ?>
                  </p>

                  <a
                    class="read-more"
                    href="<?= htmlspecialchars($articleUrl, ENT_QUOTES, 'UTF-8') ?>">
                    Read More <span>→</span>
                  </a>

                </div>

              </article>

            <?php endforeach; ?>

          <?php else: ?>

            <p class="empty-posts">
              No blog articles are currently available.
            </p>

          <?php endif; ?>

        </div>

        <!-- Dynamic Pagination -->
        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>

          <nav class="pagination" aria-label="Blog pagination">

            <?php if ($pagination['has_previous']): ?>
              <a
                class="page-btn"
                href="?page=<?= $pagination['previous_page'] ?>"
                aria-label="Previous page">
                ‹
              </a>
            <?php endif; ?>

            <?php for ($page = 1; $page <= $pagination['total_pages']; $page++): ?>

              <a
                class="page-btn <?= $page === $pagination['current_page'] ? 'active' : '' ?>"
                href="?page=<?= $page ?>"
                aria-label="Page <?= $page ?>"
                <?= $page === $pagination['current_page'] ? 'aria-current="page"' : '' ?>>
                <?= $page ?>
              </a>

            <?php endfor; ?>

            <?php if ($pagination['has_next']): ?>
              <a
                class="page-btn"
                href="?page=<?= $pagination['next_page'] ?>"
                aria-label="Next page">
                ›
              </a>
            <?php endif; ?>

          </nav>

        <?php endif; ?>

      </section>

      <!-- Newsletter Section: remains unchanged -->

      <!-- Newsletter Section -->
      <section
        class="newsletter container"
        aria-label="Newsletter subscription">

        <div class="newsletter-icon" aria-hidden="true">✉</div>

        <div class="newsletter-copy">
          <h2>Subscribe to our Newsletter</h2>

          <p>
            Get the latest articles, tutorials, and updates straight to your
            inbox.
          </p>

          <p
            class="newsletter-message"
            id="newsletterMessage"
            role="status"
            aria-live="polite"
            hidden>
          </p>
        </div>

        <form
          class="newsletter-form"
          id="newsletterForm"
          action="<?= e(url('/newsletter/subscribe')) ?>"
          method="POST">

          <input
            type="hidden"
            name="_token"
            value="<?= e(\App\Helpers\Csrf::token()) ?>">

          <input
            type="email"
            name="email"
            placeholder="Enter your email address"
            aria-label="Your email address"
            autocomplete="email"
            required>

          <button type="submit" id="newsletterSubmit">
            Subscribe
          </button>

        </form>
      </section>

    </main>

    <footer class="site-footer">
      <div class="container">
        <div class="footer-main">
          <div class="footer-cta">
            <span class="footer-eyebrow">Let's work together</span>
            <h2>
              Have a project in<br class="desktop-break" />
              mind?
            </h2>
            <p>
              I'm always open to discussing new projects, technical
              consulting, and development contracts.
            </p>
            <a class="footer-contact-btn" href="<?= e(url('/contact')) ?>">Get in Touch <span aria-hidden="true">↗</span></a>
          </div>

          <figure class="footer-testimonial">
            <div class="quote-mark" aria-hidden="true">“</div>
            <blockquote>
              “Chibuikem is an exceptional team that delivers high-quality
              work on time. Their attention to detail and problem-solving
              skills are outstanding.”
            </blockquote>
            <figcaption class="testimonial-person">
              <div class="testimonial-avatar" aria-hidden="true">
                <span></span>
              </div>
              <div>
                <strong>Sarah Johnson</strong>
                <span>CEO, TechStart</span>
              </div>
            </figcaption>
          </figure>

          <div class="footer-social">
            <h3>Follow me</h3>
            <div class="social-links">
              <a href="#" aria-label="GitHub">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path
                    d="M12 .9a11.1 11.1 0 0 0-3.51 21.63c.56.1.76-.24.76-.54v-2.1c-3.1.67-3.76-1.32-3.76-1.32-.5-1.28-1.23-1.62-1.23-1.62-1.01-.69.08-.68.08-.68 1.12.08 1.71 1.15 1.71 1.15 1 .1.76 2.22 3.84 1.59.1-.72.39-1.21.7-1.49-2.47-.28-5.07-1.24-5.07-5.5 0-1.22.44-2.21 1.15-2.99-.12-.28-.5-1.42.11-2.95 0 0 .94-.3 3.05 1.14a10.6 10.6 0 0 1 5.55 0c2.11-1.44 3.04-1.14 3.04-1.14.61 1.53.23 2.67.12 2.95.71.78 1.14 1.77 1.14 2.99 0 4.27-2.6 5.21-5.08 5.49.4.35.75 1.02.75 2.06v3.05c0 .3.2.65.77.54A11.1 11.1 0 0 0 12 .9Z" />
                </svg>
              </a>
              <a href="#" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path
                    d="M20.45 2H3.55C2.7 2 2 2.67 2 3.5v17c0 .83.7 1.5 1.55 1.5h16.9c.86 0 1.55-.67 1.55-1.5v-17c0-.83-.69-1.5-1.55-1.5ZM7.93 18.46H4.96V9.1h2.97v9.36ZM6.45 7.82a1.72 1.72 0 1 1 0-3.44 1.72 1.72 0 0 1 0 3.44Zm12.01 10.64H15.5v-4.55c0-1.08-.02-2.47-1.5-2.47-1.5 0-1.73 1.17-1.73 2.39v4.63H9.3V9.1h2.85v1.28h.04c.4-.74 1.37-1.52 2.82-1.52 3.02 0 3.58 1.99 3.58 4.58v5.02Z" />
                </svg>
              </a>
              <a href="#" aria-label="X">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path
                    d="M18.9 2H22l-6.78 7.75L23.2 22h-6.25l-4.9-7.43L5.55 22H2.4l7.25-8.29L1.8 2h6.4l4.43 6.77L18.9 2Zm-1.1 18h1.73L7.27 3.89H5.42L17.8 20Z" />
                </svg>
              </a>
              <a href="#" aria-label="Instagram">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <rect
                    x="3"
                    y="3"
                    width="18"
                    height="18"
                    rx="5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2" />
                  <circle
                    cx="12"
                    cy="12"
                    r="4"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2" />
                  <circle cx="17.6" cy="6.7" r="1.2" />
                </svg>
              </a>
            </div>
            <div class="footer-contact-list">
              <a href="mailto:chibuikemnweze2020@gmail.com"><span aria-hidden="true">✉</span>
                chibuikemnweze2020@gmail.com</a>
              <a href="tel:+2349161426400"><span aria-hidden="true">♧</span> +234 9161426400</a>
            </div>
          </div>
        </div>

        <div class="footer-bottom">
          <p>© <span id="year"></span> CiscoCraft All rights reserved.</p>
          <p>
            Made with <span class="footer-heart" aria-label="love">♥</span> by
            CiscoCraft
          </p>
        </div>
      </div>
    </footer>
  </div>



  <script>
    // =========================================================
    // 1. BLOG CATEGORY FILTERING
    // =========================================================

    const filterButtons = document.querySelectorAll(".filter-btn");
    const postCards = document.querySelectorAll(".post-card");
    const pagination = document.querySelector(".pagination");

    filterButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const category = button.dataset.filter;

        // Update active filter button.
        filterButtons.forEach((item) => {
          const selected = item === button;

          item.classList.toggle("active", selected);
          item.setAttribute("aria-pressed", String(selected));
        });

        // Filter currently displayed blog posts.
        postCards.forEach((card) => {
          card.hidden =
            category !== "all" &&
            card.dataset.category !== category;
        });

        // Hide pagination while filtering.
        if (pagination) {
          pagination.hidden = category !== "all";
        }
      });
    });


    // =========================================================
    // 2. NEWSLETTER SUBSCRIPTION
    // =========================================================

    const newsletterForm = document.getElementById("newsletterForm");
    const newsletterMessage = document.getElementById("newsletterMessage");
    const newsletterSubmit = document.getElementById("newsletterSubmit");

    if (newsletterForm) {

      newsletterForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const formData = new FormData(newsletterForm);

        const email = String(formData.get("email") || "").trim();

        if (!email) {
          showNewsletterMessage(
            "Please enter your email address.",
            "error"
          );

          return;
        }

        // Prevent repeated submissions.
        if (newsletterSubmit) {
          newsletterSubmit.disabled = true;
          newsletterSubmit.textContent = "Subscribing...";
        }

        try {
          const response = await fetch(newsletterForm.action, {
            method: "POST",
            body: formData,
            headers: {
              "Accept": "application/json",
              "X-Requested-With": "XMLHttpRequest"
            },
            credentials: "same-origin"
          });

          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(
              result.message || "Unable to complete your subscription."
            );
          }

          showNewsletterMessage(
            result.message || "Thank you for subscribing!",
            "success"
          );

          newsletterForm.reset();

        } catch (error) {

          showNewsletterMessage(
            error.message || "Something went wrong. Please try again.",
            "error"
          );

        } finally {

          // Restore the submit button.
          if (newsletterSubmit) {
            newsletterSubmit.disabled = false;
            newsletterSubmit.textContent = "Subscribe";
          }
        }
      });
    }


    // =========================================================
    // 3. NEWSLETTER MESSAGE DISPLAY
    // =========================================================

    function showNewsletterMessage(message, type) {

      if (!newsletterMessage) {
        return;
      }

      newsletterMessage.textContent = message;

      newsletterMessage.hidden = false;

      newsletterMessage.classList.remove(
        "success",
        "error"
      );

      newsletterMessage.classList.add(type);
    }


    // =========================================================
    // 4. AUTOMATIC COPYRIGHT YEAR
    // =========================================================

    const year = document.getElementById("year");

    if (year) {
      year.textContent = new Date().getFullYear();
    }
  </script>

</body>

</html>