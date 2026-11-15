<?php

$successMessage = \App\Helpers\Flash::get('success');
$errorMessage = \App\Helpers\Flash::get('error');

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact - Chibuikem Portfolio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&family=Inter:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
  <style>
    :root {
      --bg-dark: #0d0806;
      --card-bg: linear-gradient(155deg,
          rgba(36, 22, 17, 0.76) 0%,
          rgba(22, 14, 10, 0.72) 100%);
      --card-border: rgba(245, 158, 11, 0.16);
      --card-hover-border: rgba(245, 158, 11, 0.42);
      --primary: #e07a3f;
      --primary-gold: #f59e0b;
      --primary-hover: #c85e25;
      --primary-gradient: linear-gradient(135deg,
          #f59e0b 0%,
          #e07a3f 55%,
          #c65f24 100%);
      --text-main: #fcf9f6;
      --text-muted: #a89c94;
      --text-dim: #71655e;
      --badge-bg: linear-gradient(135deg,
          rgba(245, 158, 11, 0.12) 0%,
          rgba(224, 122, 63, 0.06) 100%);
      --badge-border: rgba(245, 158, 11, 0.28);
      --badge-color: #fbbf24;
    }

    /* GLOBAL RESET */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      width: 100%;
      max-width: 100%;
      scroll-behavior: smooth;
      background-color: var(--bg-dark);
      color: var(--text-main);
      font-family:
        "Inter",
        -apple-system,
        BlinkMacSystemFont,
        sans-serif;
      overflow-x: clip;
    }

    body {
      width: 100%;
      max-width: 100%;
      min-height: 100vh;
      background:
        radial-gradient(circle at 12% 15%,
          rgba(224, 122, 63, 0.08) 0%,
          transparent 45%),
        radial-gradient(circle at 88% 30%,
          rgba(198, 95, 36, 0.07) 0%,
          transparent 45%),
        radial-gradient(circle at 25% 75%,
          rgba(224, 122, 63, 0.06) 0%,
          transparent 40%),
        radial-gradient(circle at 80% 90%,
          rgba(245, 158, 11, 0.05) 0%,
          transparent 50%),
        linear-gradient(rgba(224, 122, 63, 0.018) 1px, transparent 1px),
        linear-gradient(90deg,
          rgba(224, 122, 63, 0.018) 1px,
          transparent 1px),
        #0d0806;
      background-size:
        100% 100%,
        100% 100%,
        100% 100%,
        100% 100%,
        48px 48px,
        48px 48px,
        100% 100%;
      overflow-x: clip;
      position: relative;
    }

    img,
    svg,
    video,
    canvas {
      max-width: 100%;
    }

    button,
    input,
    textarea,
    select {
      font: inherit;
    }

    .container {
      width: 100%;
      max-width: 1160px;
      margin: 0 auto;
      padding: 0 24px;
      position: relative;
      z-index: 2;
    }

    /* AMBIENT GLOW */
    .ambient-glow {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(100px);
      z-index: 0;
      max-width: 100%;
    }

    .glow-point-1 {
      top: 100px;
      left: 15%;
      width: 520px;
      height: 520px;
      background: radial-gradient(circle,
          rgba(245, 158, 11, 0.12) 0%,
          rgba(224, 122, 63, 0.05) 55%,
          transparent 75%);
    }

    .glow-point-2 {
      top: 800px;
      right: 10%;
      width: 550px;
      height: 550px;
      background: radial-gradient(circle,
          rgba(224, 122, 63, 0.1) 0%,
          rgba(245, 158, 11, 0.04) 50%,
          transparent 70%);
    }

    /* NAVIGATION */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      width: 100%;
      max-width: 100%;
      z-index: 100;
      background: rgba(13, 8, 6, 0.85);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(224, 122, 63, 0.12);
      transition: all 0.3s ease;
    }

    .nav-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 72px;
      min-width: 0;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      font-size: 19px;
      font-weight: 700;
      color: #ffffff;
      min-width: 0;
    }

    .nav-logo-icon {
      color: var(--primary);
      font-family: "Fira Code", monospace;
      font-weight: 700;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 30px;
      list-style: none;
    }

    .nav-link {
      text-decoration: none;
      color: var(--text-muted);
      font-size: 14px;
      font-weight: 500;
      transition: color 0.2s ease;
      position: relative;
      padding: 6px 0;
    }

    .nav-link:hover,
    .nav-link.active {
      color: #ffffff;
    }

    .nav-link.active::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: var(--primary);
      border-radius: 2px;
    }

    .btn-hire {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      background: var(--primary-gradient);
      color: #ffffff;
      font-size: 14px;
      font-weight: 600;
      padding: 9px 20px;
      border-radius: 9999px;
      text-decoration: none;
      transition: all 0.2s ease;
      box-shadow: 0 4px 16px rgba(224, 122, 63, 0.35);
    }

    .btn-hire:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(224, 122, 63, 0.5);
    }

    /* BADGES AND SECTION HEADINGS */
    .pill-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.4px;
      text-transform: uppercase;
      color: var(--badge-color);
      background: var(--badge-bg);
      border: 1px solid var(--badge-border);
      padding: 6px 14px;
      border-radius: 9999px;
      margin-bottom: 18px;
    }

    .section-header-center {
      text-align: center;
      margin-bottom: 50px;
    }

    .section-title {
      font-size: 36px;
      font-weight: 800;
      letter-spacing: -0.5px;
      color: #ffffff;
      line-height: 1.2;
      position: relative;
      display: inline-block;
      max-width: 100%;
    }

    .section-title-underline {
      width: 44px;
      height: 3px;
      background: linear-gradient(90deg,
          #fbbf24 0%,
          #f59e0b 50%,
          #e07a3f 100%);
      box-shadow: 0 0 12px rgba(245, 158, 11, 0.4);
      border-radius: 2px;
      margin: 10px auto 0;
    }

    /* CONTACT HERO */
    .contact-hero-section {
      padding-top: 150px;
      padding-bottom: 90px;
      position: relative;
      overflow: hidden;
      width: 100%;
    }

    .contact-watermark {
      position: absolute;
      top: 80px;
      left: 50%;
      transform: translateX(-50%);
      font-size: clamp(60px, 16vw, 210px);
      font-weight: 900;
      letter-spacing: 12px;
      color: rgba(255, 255, 255, 0.022);
      text-transform: uppercase;
      user-select: none;
      pointer-events: none;
      z-index: 0;
      white-space: nowrap;
      max-width: 100%;
    }

    .contact-solar-orb {
      position: absolute;
      top: 180px;
      left: 45%;
      transform: translateX(-50%);
      width: 460px;
      height: 460px;
      max-width: 100vw;
      border-radius: 50%;
      background: radial-gradient(circle,
          rgba(245, 158, 11, 0.22) 0%,
          rgba(224, 122, 63, 0.08) 50%,
          transparent 70%);
      filter: blur(50px);
      pointer-events: none;
      z-index: 0;
    }

    .contact-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1.08fr);
      gap: 50px;
      align-items: center;
      position: relative;
      z-index: 2;
      width: 100%;
      min-width: 0;
    }

    .contact-left-col {
      display: flex;
      flex-direction: column;
      min-width: 0;
      max-width: 100%;
    }

    .contact-badge-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(36, 22, 16, 0.8);
      border: 1px solid rgba(224, 122, 63, 0.3);
      padding: 7px 16px;
      border-radius: 9999px;
      width: fit-content;
      max-width: 100%;
      font-size: 12px;
      font-weight: 600;
      color: #e2d9d2;
      margin-bottom: 24px;
      backdrop-filter: blur(8px);
    }

    .contact-badge-pill svg {
      color: var(--primary);
      flex-shrink: 0;
    }

    .contact-main-heading {
      font-size: 54px;
      font-weight: 800;
      letter-spacing: -1px;
      color: #ffffff;
      line-height: 1.15;
      margin-bottom: 16px;
      overflow-wrap: anywhere;
    }

    .contact-main-subtext {
      font-size: 15.5px;
      line-height: 1.6;
      color: var(--text-muted);
      margin-bottom: 40px;
      max-width: 440px;
      overflow-wrap: anywhere;
    }

    /* CONTACT METHOD CARDS */
    .contact-cards-stack {
      display: flex;
      flex-direction: column;
      gap: 16px;
      width: 100%;
      min-width: 0;
    }

    .contact-method-card {
      background: var(--card-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid var(--card-border);
      border-radius: 20px;
      padding: 18px 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      min-width: 0;
      max-width: 100%;
      text-decoration: none;
      box-shadow:
        inset 0 1px 0 0 rgba(245, 158, 11, 0.16),
        0 14px 30px rgba(0, 0, 0, 0.35);
      transition: all 0.25s ease;
    }

    .contact-method-card:hover {
      border-color: var(--card-hover-border);
      box-shadow:
        inset 0 1px 0 0 rgba(245, 158, 11, 0.35),
        0 18px 36px rgba(0, 0, 0, 0.5),
        0 0 20px rgba(245, 158, 11, 0.12);
      transform: translateY(-2px);
    }

    .method-card-left {
      display: flex;
      align-items: center;
      gap: 16px;
      min-width: 0;
      flex: 1;
    }

    .method-icon-box {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      background: rgba(224, 122, 63, 0.1);
      border: 1px solid rgba(224, 122, 63, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      flex-shrink: 0;
    }

    .method-text-wrap {
      display: flex;
      flex-direction: column;
      gap: 3px;
      min-width: 0;
    }

    .method-label {
      font-size: 15px;
      font-weight: 700;
      color: #ffffff;
    }

    .method-value {
      font-size: 13.5px;
      color: var(--text-muted);
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    .method-arrow-btn {
      width: 38px;
      height: 38px;
      border-radius: 12px;
      background: rgba(224, 122, 63, 0.08);
      border: 1px solid rgba(224, 122, 63, 0.18);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      transition: all 0.2s ease;
      flex-shrink: 0;
    }

    .contact-method-card:hover .method-arrow-btn {
      background: var(--primary);
      color: #0f0907;
      transform: translate(2px, -2px);
    }

    /* CONTACT FORM */
    .contact-form-card {
      width: 100%;
      min-width: 0;
      max-width: 100%;
      background: var(--card-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(245, 158, 11, 0.22);
      border-radius: 26px;
      padding: 32px;
      box-shadow:
        inset 0 1px 0 0 rgba(245, 158, 11, 0.22),
        0 24px 60px rgba(0, 0, 0, 0.55),
        0 0 35px rgba(245, 158, 11, 0.06);
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .form-input-field {
      display: block;
      width: 100%;
      min-width: 0;
      max-width: 100%;
      background: rgba(18, 11, 8, 0.72);
      border: 1px solid rgba(224, 122, 63, 0.22);
      border-radius: 16px;
      padding: 16px 20px;
      font-size: 14.5px;
      color: #ffffff;
      font-family: inherit;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-input-field::placeholder {
      color: #8a7c73;
    }

    .form-input-field:focus {
      border-color: var(--primary-gold);
      background: rgba(22, 13, 9, 0.85);
      box-shadow: 0 0 18px rgba(245, 158, 11, 0.2);
    }

    textarea.form-input-field {
      resize: vertical;
      min-height: 170px;
    }

    .contact-submit-btn {
      display: block;
      width: 100%;
      max-width: 100%;
      background: linear-gradient(135deg,
          #f59e0b 0%,
          #e07a3f 60%,
          #c65f24 100%);
      color: #120a07;
      font-size: 16px;
      font-weight: 800;
      letter-spacing: 0.3px;
      border: none;
      border-radius: 9999px;
      padding: 16px;
      cursor: pointer;
      transition: all 0.25s ease;
      box-shadow: 0 6px 22px rgba(245, 158, 11, 0.35);
      margin-top: 4px;
    }

    .contact-submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(245, 158, 11, 0.5);
    }

    .form-status-msg {
      display: none;
      font-size: 13.5px;
      color: #34d399;
      text-align: center;
      padding-top: 6px;
      font-weight: 600;
    }

    /* FAQ SECTION */
    .faq-section {
      padding: 90px 0 100px;
      border-top: 1px solid rgba(224, 122, 63, 0.12);
      position: relative;
      width: 100%;
    }

    .faq-status-strip {
      display: flex;
      justify-content: center;
      margin-bottom: 46px;
      padding: 0 12px;
    }

    .availability-pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      gap: 10px;
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      padding: 10px 22px;
      border-radius: 9999px;
      font-size: 13.5px;
      color: var(--text-main);
      backdrop-filter: blur(12px);
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.15);
      max-width: 100%;
      text-align: center;
    }

    .status-dot-green {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      background: #10b981;
      box-shadow: 0 0 10px #10b981;
      flex-shrink: 0;
    }

    .faq-2x2-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 24px;
      width: 100%;
      min-width: 0;
    }

    .faq-card {
      min-width: 0;
      max-width: 100%;
      background: var(--card-bg);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid var(--card-border);
      border-radius: 20px;
      padding: 28px;
      box-shadow:
        inset 0 1px 0 0 rgba(245, 158, 11, 0.16),
        0 14px 30px rgba(0, 0, 0, 0.35);
      transition: all 0.25s ease;
    }

    .faq-card:hover {
      border-color: var(--card-hover-border);
      box-shadow:
        inset 0 1px 0 0 rgba(245, 158, 11, 0.35),
        0 18px 36px rgba(0, 0, 0, 0.5),
        0 0 20px rgba(245, 158, 11, 0.1);
      transform: translateY(-3px);
    }

    .faq-question {
      font-size: 17px;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 12px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      line-height: 1.35;
      min-width: 0;
    }

    .faq-question-num {
      color: var(--primary-gold);
      font-family: "Fira Code", monospace;
      font-size: 13.5px;
      padding-top: 2px;
      flex-shrink: 0;
    }

    .faq-answer {
      font-size: 14px;
      color: var(--text-muted);
      line-height: 1.65;
      padding-left: 32px;
      overflow-wrap: anywhere;
    }

    /* FOOTER */
    .contact-section {
      width: 100%;
      padding: 90px 0 45px;
      border-top: 1px solid rgba(224, 122, 63, 0.12);
      position: relative;
    }

    .contact-3col-grid {
      display: grid;
      grid-template-columns: minmax(0, 1.15fr) minmax(0, 1.35fr) minmax(0,
          1fr);
      gap: 40px;
      margin-bottom: 60px;
      width: 100%;
      min-width: 0;
    }

    .contact-cta,
    .testimonial-card,
    .contact-3col-grid>div {
      min-width: 0;
    }

    .contact-cta h2 {
      font-size: 30px;
      font-weight: 800;
      margin-bottom: 14px;
      line-height: 1.25;
      overflow-wrap: anywhere;
    }

    .contact-cta p {
      font-size: 14.5px;
      line-height: 1.65;
      color: var(--text-muted);
      margin-bottom: 24px;
      overflow-wrap: anywhere;
    }

    .testimonial-card {
      background: var(--card-bg);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid var(--card-border);
      border-radius: 18px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow:
        inset 0 1px 0 0 rgba(245, 158, 11, 0.18),
        0 16px 36px rgba(0, 0, 0, 0.45);
    }

    .testimonial-quote-icon {
      color: var(--primary);
      font-size: 32px;
      line-height: 1;
      margin-bottom: 8px;
      font-family: Georgia, serif;
    }

    .testimonial-text {
      font-size: 14px;
      line-height: 1.65;
      color: #ede5df;
      margin-bottom: 20px;
      font-style: italic;
      overflow-wrap: anywhere;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
    }

    .author-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid rgba(224, 122, 63, 0.4);
      flex-shrink: 0;
    }

    .author-name {
      font-size: 14px;
      font-weight: 700;
      color: #ffffff;
      overflow-wrap: anywhere;
    }

    .author-role {
      font-size: 12px;
      color: var(--text-muted);
    }

    .follow-col-title {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 1.5px;
      color: var(--text-muted);
      text-transform: uppercase;
      margin-bottom: 18px;
    }

    .social-icons-row {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 22px;
    }

    .social-btn {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: rgba(28, 18, 14, 0.6);
      border: 1px solid var(--card-border);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      text-decoration: none;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }

    .social-btn:hover {
      color: #ffffff;
      border-color: var(--primary);
      background: rgba(224, 122, 63, 0.16);
      transform: translateY(-2px);
    }

    .contact-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 13.5px;
      color: var(--text-muted);
      margin-bottom: 12px;
      text-decoration: none;
      transition: color 0.2s ease;
      min-width: 0;
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    .contact-item:hover {
      color: #ffffff;
    }

    .contact-item svg {
      color: var(--primary);
      flex-shrink: 0;
    }

    .sub-footer {
      border-top: 1px solid rgba(224, 122, 63, 0.1);
      padding-top: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      font-size: 13px;
      color: var(--text-dim);
      min-width: 0;
    }

    .sub-footer>* {
      min-width: 0;
      overflow-wrap: anywhere;
    }

    /* TABLET RESPONSIVENESS */
    @media (max-width: 968px) {

      .contact-grid,
      .faq-2x2-grid,
      .contact-3col-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 36px;
      }

      .contact-main-heading {
        font-size: 40px;
      }

      .nav-links {
        display: none;
      }

      .contact-3col-grid {
        margin-bottom: 40px;
      }
    }

    /* MOBILE RESPONSIVENESS */
    @media (max-width: 600px) {

      html,
      body {
        width: 100%;
        max-width: 100%;
        overflow-x: clip;
      }

      .container {
        width: 100%;
        max-width: 100%;
        padding-left: 18px;
        padding-right: 18px;
      }

      .nav-inner {
        height: 64px;
      }

      .nav-logo {
        font-size: 17px;
      }

      .btn-hire {
        font-size: 12px;
        padding: 8px 14px;
      }

      .contact-hero-section {
        padding-top: 115px;
        padding-bottom: 65px;
      }

      .contact-watermark {
        top: 65px;
        font-size: clamp(48px, 14vw, 85px);
        letter-spacing: 5px;
      }

      .contact-solar-orb {
        top: 160px;
        left: 50%;
        width: 300px;
        height: 300px;
        filter: blur(40px);
      }

      .contact-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 36px;
      }

      .contact-main-heading {
        font-size: clamp(34px, 9vw, 42px);
        letter-spacing: -0.8px;
      }

      .contact-main-subtext {
        font-size: 14px;
        margin-bottom: 28px;
      }

      .contact-cards-stack {
        gap: 12px;
      }

      .contact-method-card {
        padding: 15px;
        gap: 10px;
        border-radius: 16px;
      }

      .method-card-left {
        gap: 12px;
      }

      .method-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
      }

      .method-label {
        font-size: 14px;
      }

      .method-value {
        font-size: 12px;
      }

      .method-arrow-btn {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
      }

      .contact-form-card {
        padding: 20px 16px;
        border-radius: 20px;
        gap: 14px;
      }

      .form-input-field {
        padding: 14px 15px;
        font-size: 14px;
        border-radius: 13px;
      }

      textarea.form-input-field {
        min-height: 140px;
      }

      .contact-submit-btn {
        font-size: 14px;
        padding: 14px;
      }

      .faq-section {
        padding: 65px 0;
      }

      .section-header-center {
        margin-bottom: 32px;
      }

      .section-title {
        font-size: clamp(27px, 7vw, 34px);
      }

      .faq-status-strip {
        margin-bottom: 30px;
      }

      .availability-pill {
        font-size: 12px;
        padding: 10px 14px;
      }

      .faq-2x2-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 16px;
      }

      .faq-card {
        padding: 21px 18px;
        border-radius: 16px;
      }

      .faq-question {
        font-size: 15px;
      }

      .faq-answer {
        font-size: 13px;
        padding-left: 27px;
      }

      .contact-section {
        padding: 60px 0 28px;
      }

      .contact-3col-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 30px;
        margin-bottom: 35px;
      }

      .contact-cta h2 {
        font-size: 26px;
      }

      .testimonial-card {
        padding: 20px;
      }

      .sub-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding-top: 20px;
        font-size: 12px;
      }

      .glow-point-1,
      .glow-point-2 {
        width: 280px;
        height: 280px;
        filter: blur(65px);
      }
    }

    /* SMALL PHONES */
    @media (max-width: 380px) {
      .container {
        padding-left: 14px;
        padding-right: 14px;
      }

      .contact-main-heading {
        font-size: 32px;
      }

      .contact-method-card {
        padding: 12px;
      }

      .method-card-left {
        gap: 9px;
      }

      .method-icon-box {
        width: 38px;
        height: 38px;
      }

      .method-value {
        font-size: 11px;
      }

      .contact-form-card {
        padding: 17px 13px;
      }

      .faq-card {
        padding: 18px 15px;
      }
    }
  </style>
</head>

<body>
  <!-- Ambient Gold-Orange Glow Points (Low Opacity) -->
 
<div class="ambient-glow glow-point-1"></div>
<div class="ambient-glow glow-point-2"></div>

<!-- NAVBAR -->
<header class="navbar">
  <div class="container nav-inner">
    <a href="<?= e(url('/')) ?>" class="nav-logo">
      <span class="nav-logo-icon">&lt;/&gt;</span>
      <span>CiscoCraft</span>
    </a>
    <nav>
      <ul class="nav-links">
        <li><a href="<?= e(url('/#home')) ?>" class="nav-link active">Home</a></li>
        <li><a href="<?= e(url('/#about')) ?>" class="nav-link">About</a></li>
        <li><a href="<?= e(url('/#skills')) ?>" class="nav-link">Skills</a></li>
        <li><a href="<?= e(url('/projects')) ?>" class="nav-link">Projects</a></li>
        <li><a href="<?= e(url('/blog')) ?>" class="nav-link">Blog</a></li>
        <li><a href="<?= e(url('/contact')) ?>" class="nav-link">Contact</a></li>
      </ul>
    </nav>
    <a href="<?= e(url('/contact')) ?>" class="btn-hire">
      Hire Me
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2.5">
        <path d="M7 17L17 7M17 7H7M17 7V17" />
      </svg>
    </a>
  </div>
</header>

<main>
  <!-- SECTION 1: CONTACT SHOWCASE -->
  <section class="contact-hero-section">
    <div class="contact-watermark">CONTACT</div>
    <div class="contact-solar-orb"></div>

    <div class="container">
      <div class="contact-grid">

        <!-- Left Column -->
        <div class="contact-left-col">
          <div class="contact-badge-pill">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <circle cx="12" cy="12" r="6" />
              <circle cx="12" cy="12" r="2" />
            </svg>
            <span>Contact</span>
          </div>

          <h1 class="contact-main-heading">Get in touch</h1>
          <p class="contact-main-subtext">
            Have questions or ready to transform your business with modern
            web engineering &amp; digital solutions?
          </p>

          <div class="contact-cards-stack">

            <!-- Email -->
            <a href="mailto:chibuikemnweze2020@gmail.com"
              class="contact-method-card">
              <div class="method-card-left">
                <div class="method-icon-box">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <path d="M22 7l-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                  </svg>
                </div>
                <div class="method-text-wrap">
                  <span class="method-label">Email us</span>
                  <span class="method-value">chibuikemnweze2020@gmail.com</span>
                </div>
              </div>
              <div class="method-arrow-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2.5">
                  <path d="M7 17L17 7M17 7H7M17 7V17" />
                </svg>
              </div>
            </a>

            <!-- Phone -->
            <a href="tel:+2349161426400" class="contact-method-card">
              <div class="method-card-left">
                <div class="method-icon-box">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                  </svg>
                </div>
                <div class="method-text-wrap">
                  <span class="method-label">Call us</span>
                  <span class="method-value">+234 9161426400</span>
                </div>
              </div>
              <div class="method-arrow-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2.5">
                  <path d="M7 17L17 7M17 7H7M17 7V17" />
                </svg>
              </div>
            </a>

            <!-- Location -->
            <div class="contact-method-card">
              <div class="method-card-left">
                <div class="method-icon-box">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                    <circle cx="12" cy="10" r="3" />
                  </svg>
                </div>
                <div class="method-text-wrap">
                  <span class="method-label">Our location</span>
                  <span class="method-value">No 82 eletebera estate edem road nsukka, Nsukka, Enugu, Nigeria</span>
                </div>
              </div>
              <div class="method-arrow-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2.5">
                  <path d="M7 17L17 7M17 7H7M17 7V17" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Contact Form -->
        <div class="contact-form-card">

          <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
              <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error">
              <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>

          <form
            id="contactForm"
            action="<?= htmlspecialchars(url('/contact'), ENT_QUOTES, 'UTF-8') ?>"
            method="POST">

            <input
              type="hidden"
              name="_token"
              value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>" />

            <div style="display: flex; flex-direction: column; gap: 16px">
              <input
                type="text"
                id="senderName"
                name="name"
                class="form-input-field"
                placeholder="Name"
                required />

              <input
                type="email"
                id="senderEmail"
                name="email"
                class="form-input-field"
                placeholder="Email"
                required />

              <textarea
                id="senderMessage"
                name="message"
                class="form-input-field"
                placeholder="Message"
                rows="5"
                required></textarea>

              <button type="submit" class="contact-submit-btn">
                Submit
              </button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </section>

  <!-- SECTION 2: FAQ -->
  <section class="faq-section">
    <div class="container">
      <div class="section-header-center">
        <div class="pill-badge">FAQ &amp; DETAILS</div>
        <h2 class="section-title">Working Together: What to Expect</h2>
        <div class="section-title-underline"></div>
      </div>

      <div class="faq-status-strip">
        <div class="availability-pill">
          <span class="status-dot-green"></span>
          <span><strong>Current Status:</strong> Open for select contracts
            &bull; Response time: under 12 hours</span>
        </div>
      </div>

      <div class="faq-2x2-grid">

        <div class="faq-card">
          <h3 class="faq-question">
            <span class="faq-question-num">01.</span>
            <span>What is your typical project timeline?</span>
          </h3>
          <p class="faq-answer">
            Most custom web applications, SaaS dashboards, and e-commerce
            stores take between 2 to 6 weeks from initial architecture to
            final production rollout, structured around weekly demo sprints.
          </p>
        </div>

        <div class="faq-card">
          <h3 class="faq-question">
            <span class="faq-question-num">02.</span>
            <span>What technical stack do you specialize in?</span>
          </h3>
          <p class="faq-answer">
            I build production-grade web applications with Next.js 14, React
            18, TypeScript, Tailwind CSS, Node.js, PostgreSQL, and cloud
            deployments on Vercel and AWS, audited for 99+ Lighthouse
            performance.
          </p>
        </div>

        <div class="faq-card">
          <h3 class="faq-question">
            <span class="faq-question-num">03.</span>
            <span>How do communication and milestone reviews work?</span>
          </h3>
          <p class="faq-answer">
            We stay in direct sync via a dedicated Slack or Discord channel,
            weekly async Loom progress videos, and live staging URLs so you
            can test features in real-time as they are coded.
          </p>
        </div>

        <div class="faq-card">
          <h3 class="faq-question">
            <span class="faq-question-num">04.</span>
            <span>Do you provide warranty and post-launch maintenance?</span>
          </h3>
          <p class="faq-answer">
            Yes, all projects include 30 days of complimentary post-launch
            bug fixing and support, along with optional monthly maintenance
            retainers for continuous feature iterations.
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <section class="contact-section">
    <div class="container">
      <div class="contact-3col-grid">

        <!-- Left: Let's Work Together -->
        <div class="contact-cta">
          <div class="pill-badge">LET'S WORK TOGETHER</div>
          <h2>Have a project in mind?</h2>
          <p>
            I'm always open to discussing new projects, technical
            consulting, and development contracts. Let's create something
            amazing together!
          </p>
        </div>

        <!-- Middle: Testimonial -->
        <div class="testimonial-card">
          <div>
            <div class="testimonial-quote-icon">“</div>
            <p class="testimonial-text">
              "Chibuikem is an exceptional developer who delivers
              high-quality work on time. His attention to detail and
              problem-solving skills are outstanding."
            </p>
          </div>
          <div class="testimonial-author">
            <svg class="author-avatar" viewBox="0 0 40 40" width="40" height="40">
              <circle cx="20" cy="20" r="20" fill="#2d170e" />
              <circle cx="20" cy="15" r="7" fill="#f5d0b8" />
              <path d="M6 35c0-7.7 6.3-14 14-14s14 6.3 14 14" fill="#e07a3f" />
            </svg>
            <div>
              <div class="author-name">Sarah Johnson</div>
              <div class="author-role">CEO, TechStart</div>
            </div>
          </div>
        </div>

        <!-- Right: Social and Contact -->
        <div>
          <div class="follow-col-title">FOLLOW ME</div>
          <div class="social-icons-row">

            <a href="https://github.com" target="_blank" rel="noreferrer"
              class="social-btn" title="GitHub">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 1.23c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z" />
              </svg>
            </a>

            <a href="https://linkedin.com" target="_blank" rel="noreferrer"
              class="social-btn" title="LinkedIn">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.6a1.64 1.64 0 0 0-1.64 1.64c0 .91.73 1.64 1.64 1.64s1.64-.73 1.64-1.64A1.64 1.64 0 0 0 7.83 6.6z" />
              </svg>
            </a>

            <a href="https://twitter.com" target="_blank" rel="noreferrer"
              class="social-btn" title="Twitter/X">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
              </svg>
            </a>

            <a href="https://instagram.com" target="_blank" rel="noreferrer"
              class="social-btn" title="Instagram">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
              </svg>
            </a>
          </div>

          <div>
            <a href="mailto:chibuikemnweze2020@gmail.com" class="contact-item">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                <polyline points="22,6 12,13 2,6" />
              </svg>
              <span>chibuikemnweze2020@gmail.com</span>
            </a>

            <a href="tel:+2349161426400" class="contact-item">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
              </svg>
              <span>+234 9161426400</span>
            </a>
          </div>
        </div>
      </div>

      <div class="sub-footer">
        <div>&copy; 2024 CiscoCraft Developer. All rights reserved.</div>
        <div>Made with ❤️ by CiscoCraft</div>
      </div>
    </div>
  </section>
</main>



</body>

</html>