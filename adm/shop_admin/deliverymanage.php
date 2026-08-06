<?php
$sub_menu = '400760';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '배송관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<style>
    :root {
        --bg: #f5f6f8;
        --surface: #ffffff;
        --surface-2: #fafafa;
        --line: #e7e9ed;
        --line-strong: #d8dce2;
        --text: #1c2028;
        --muted: #717782;
        --muted-2: #9aa0aa;
        --brand: #ff6a3d;
        --brand-dark: #e84d21;
        --brand-soft: #fff1eb;
        --green: #178a5b;
        --green-soft: #eaf8f1;
        --blue: #4169e1;
        --blue-soft: #eef2ff;
        --yellow: #9b6600;
        --yellow-soft: #fff8df;
        --red: #dc3f4b;
        --shadow: 0 12px 32px rgba(24, 29, 39, .08);
        --radius: 14px;
    }

    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        min-width: 320px;
        color: var(--text);
        background: var(--bg);
        font-family: Pretendard, "Noto Sans KR", "Apple SD Gothic Neo", Arial, sans-serif;
        font-size: 14px;
        line-height: 1.55;
        word-break: keep-all;
    }

    button,
    input,
    select {
        font: inherit;
    }

    button {
        cursor: pointer;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    .app-shell {
        min-height: 100vh;
    }

    .subnav {
        margin: 4px 0 14px 21px;
        padding-left: 19px;
        border-left: 1px solid var(--line);
    }

    .subnav a {
        display: block;
        padding: 7px 0;
        color: var(--muted);
        font-size: 13px;
    }

    .subnav a.active {
        color: var(--brand-dark);
        font-weight: 750;
    }

    /* .main {
      margin-left: 232px;
      padding: 104px 32px 64px;
    } */

    /* .content {
        width: min(1180px, 100%);
        margin: 0 auto;
    } */

    .page-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 16px;
    }

    /* h1,
    h2,
    h3,
    p {
        margin-top: 0;
    } */

    /* h1 {
        margin-bottom: 7px;
        font-size: 27px;
        line-height: 1.25;
        letter-spacing: -.04em;
    } */

    .page-head p {
        margin-bottom: 0;
        color: var(--muted);
    }

    .btn {
        min-height: 40px;
        padding: 0 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border: 1px solid var(--line-strong);
        border-radius: 9px;
        color: #343943;
        background: #fff;
        font-weight: 720;
        white-space: nowrap;
        transition: .18s ease;
    }

    .btn:hover {
        border-color: #bcc1c9;
        background: #fafafa;
    }

    .btn:focus-visible,
    .field:focus-visible,
    select:focus-visible {
        outline: 3px solid rgba(255, 106, 61, .16);
        outline-offset: 1px;
        border-color: var(--brand);
    }

    .btn-primary {
        color: #fff;
        border-color: #252932;
        background: #252932;
    }

    .btn-primary:hover {
        color: #fff;
        border-color: #11141a;
        background: #11141a;
        transform: translateY(-1px);
    }

    .btn-brand {
        color: #fff;
        border-color: var(--brand);
        background: var(--brand);
    }

    .btn-brand:hover {
        color: #fff;
        border-color: var(--brand-dark);
        background: var(--brand-dark);
    }

    .btn-ghost {
        border-color: transparent;
        background: transparent;
    }

    .btn-small {
        min-height: 32px;
        padding: 0 10px;
        border-radius: 7px;
        font-size: 12px;
    }

    .btn-icon {
        width: 40px;
        padding: 0;
    }

    .plus {
        font-size: 18px;
        font-weight: 500;
        line-height: 1;
    }

    .tabs {
        display: flex;
        align-items: center;
        gap: 28px;
        margin-bottom: 22px;
        border-bottom: 1px solid var(--line);
    }

    .tab {
        position: relative;
        padding: 0 1px 13px;
        border: 0;
        color: var(--muted);
        background: transparent;
        font-weight: 720;
    }

    .tab span {
        margin-left: 4px;
        color: var(--muted-2);
        font-size: 12px;
    }

    .tab.active {
        color: var(--text);
    }

    .tab.active::after {
        content: "";
        position: absolute;
        height: 2px;
        inset: auto 0 -1px;
        border-radius: 3px;
        background: var(--brand);
    }

    .panel {
        display: none;
    }

    .panel.active {
        display: block;
    }

    .guide {
        margin-bottom: 18px;
        padding: 21px 22px;
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 28px;
        border: 1px solid #f2d8cd;
        border-radius: var(--radius);
        background: linear-gradient(110deg, #fff9f6, #fff);
    }

    .guide h2 {
        margin-bottom: 6px;
        font-size: 16px;
        letter-spacing: -.025em;
    }

    .guide p {
        margin-bottom: 0;
        color: var(--muted);
        font-size: 13px;
    }

    .flow {
        display: grid;
        grid-template-columns: 1fr 32px 1fr 32px 1fr;
        align-items: center;
        gap: 7px;
    }

    .flow-step {
        min-height: 64px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
    }

    .step-no {
        width: 26px;
        height: 26px;
        display: grid;
        place-items: center;
        flex: none;
        border-radius: 50%;
        color: var(--brand-dark);
        background: var(--brand-soft);
        font-size: 12px;
        font-weight: 850;
    }

    .flow-step strong {
        display: block;
        font-size: 13px;
    }

    .flow-step small {
        display: block;
        margin-top: 2px;
        color: var(--muted);
        font-size: 11px;
    }

    .flow-arrow {
        color: #bbc0c8;
        text-align: center;
        font-size: 20px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 18px;
    }

    .summary-card {
        padding: 18px 19px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
    }

    .summary-card .label {
        color: var(--muted);
        font-size: 12px;
        font-weight: 650;
    }

    .summary-card .value {
        margin: 5px 0 1px;
        font-size: 23px;
        font-weight: 820;
        letter-spacing: -.04em;
    }

    .summary-card .sub {
        color: var(--muted-2);
        font-size: 11px;
    }

    .card {
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: var(--surface);
    }

    .card-head {
        min-height: 68px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        border-bottom: 1px solid var(--line);
    }

    .card-head h2 {
        margin-bottom: 2px;
        font-size: 16px;
        letter-spacing: -.02em;
    }

    .card-head p {
        margin-bottom: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .filters {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-wrap {
        position: relative;
    }

    .search-wrap::before {
        content: "⌕";
        position: absolute;
        left: 12px;
        top: 50%;
        color: var(--muted);
        transform: translateY(-52%) rotate(-15deg);
        font-size: 17px;
    }

    .field,
    .select {
        height: 38px;
        padding: 0 12px;
        border: 1px solid var(--line-strong);
        border-radius: 8px;
        color: var(--text);
        background: #fff;
    }

    .search-field {
        width: 205px;
        padding-left: 34px;
    }

    .select {
        padding-right: 30px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 880px;
    }

    th,
    td {
        padding: 15px 16px;
        border-bottom: 1px solid #eef0f2;
        text-align: left;
        vertical-align: middle;
    }

    th {
        color: var(--muted);
        background: #fbfbfc;
        font-size: 11px;
        font-weight: 760;
    }

    tbody tr:last-child td {
        border-bottom: 0;
    }

    tbody tr:hover td {
        background: #fdfdfd;
    }

    .condition-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .condition-name strong {
        font-size: 13px;
    }

    .condition-desc {
        display: block;
        margin-top: 3px;
        color: var(--muted);
        font-size: 11px;
    }

    .badge {
        min-height: 22px;
        padding: 2px 7px;
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        color: #5a616b;
        background: #f0f1f3;
        font-size: 10px;
        font-weight: 780;
        white-space: nowrap;
    }

    .badge-base {
        color: var(--brand-dark);
        background: var(--brand-soft);
    }

    .badge-bundle {
        color: var(--green);
        background: var(--green-soft);
    }

    .badge-individual {
        color: var(--blue);
        background: var(--blue-soft);
    }

    .badge-warning {
        color: var(--yellow);
        background: var(--yellow-soft);
    }

    .badge-off {
        color: #777;
        background: #efefef;
    }

    .usage {
        color: var(--text);
        border: 0;
        border-bottom: 1px solid #aeb4bd;
        background: none;
        font-weight: 750;
    }

    .row-actions {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .text-btn {
        padding: 4px 6px;
        border: 0;
        color: #59606a;
        background: transparent;
        font-size: 12px;
        font-weight: 700;
    }

    .text-btn:hover {
        color: var(--brand-dark);
    }

    .kebab {
        color: #8c929b;
        font-size: 18px;
        letter-spacing: 1px;
    }

    .btn:disabled {
        color: #a4a9b1;
        border-color: #e1e3e6;
        background: #f2f3f4;
        cursor: not-allowed;
    }

    .table-foot {
        min-height: 52px;
        padding: 0 17px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--line);
        color: var(--muted);
        font-size: 12px;
    }

    .pagination {
        display: flex;
        gap: 4px;
    }

    .page-btn {
        width: 30px;
        height: 30px;
        border: 1px solid transparent;
        border-radius: 7px;
        color: var(--muted);
        background: transparent;
    }

    .page-btn.active {
        color: var(--text);
        border-color: var(--line);
        background: #fff;
        font-weight: 750;
    }

    .rule-note {
        margin-top: 14px;
        padding: 13px 15px;
        display: flex;
        align-items: flex-start;
        gap: 9px;
        border: 1px solid #f0e2b0;
        border-radius: 10px;
        color: #69531a;
        background: var(--yellow-soft);
        font-size: 12px;
    }

    .info-dot {
        width: 18px;
        height: 18px;
        display: grid;
        place-items: center;
        flex: none;
        border: 1px solid currentColor;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 800;
    }

    .empty-search {
        display: none;
        padding: 48px 20px;
        color: var(--muted);
        text-align: center;
    }

    .group-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .group-card {
        padding: 19px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: #fff;
    }

    .group-top {
        display: flex;
        justify-content: space-between;
        gap: 14px;
    }

    .group-card h3 {
        margin-bottom: 5px;
        font-size: 15px;
    }

    .group-card p {
        margin-bottom: 15px;
        color: var(--muted);
        font-size: 12px;
    }

    .group-meta {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }

    .group-meta span {
        display: block;
        color: var(--muted-2);
        font-size: 10px;
    }

    .group-meta strong {
        display: block;
        margin-top: 2px;
        font-size: 12px;
    }

    .product-list-toolbar {
        margin-bottom: 10px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-radius: 9px;
        background: #f6f7f8;
        color: var(--muted);
        font-size: 11px;
    }

    .product-list-toolbar label {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #535a64;
        font-weight: 750;
        cursor: pointer;
    }

    .product-list {
        display: grid;
        gap: 8px;
    }

    .product-row {
        padding: 13px 14px;
        display: flex;
        align-items: center;
        gap: 13px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
        transition: border-color .16s ease, box-shadow .16s ease, background .16s ease;
    }

    .product-row:hover {
        border-color: #c7cbd1;
        box-shadow: 0 4px 12px rgba(28, 33, 41, .05);
    }

    .product-row:focus-visible {
        outline: 2px solid var(--brand);
        outline-offset: 2px;
    }

    .product-row.selected {
        border-color: #f0a082;
        background: #fffaf8;
    }

    .row-check {
        width: 17px;
        height: 17px;
        flex: none;
        accent-color: var(--brand);
        cursor: pointer;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .product-thumb {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: 9px;
        color: #a55b35;
        background: #f7e3d5;
        font-size: 19px;
    }

    .product-info strong {
        display: block;
        font-size: 13px;
    }

    .product-info small {
        display: block;
        margin-top: 2px;
        color: var(--muted);
    }

    .product-actions {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .product-fee {
        min-width: 82px;
        text-align: right;
    }

    .product-fee strong {
        display: block;
        font-size: 13px;
    }

    .product-fee small {
        color: var(--muted);
    }

    .empty-products {
        display: none;
        padding: 42px 16px;
        border: 1px dashed var(--line-strong);
        border-radius: 10px;
        color: var(--muted);
        text-align: center;
        font-size: 12px;
    }

    .overlay {
        position: fixed;
        z-index: 1000;
        inset: 0;
        visibility: hidden;
        opacity: 0;
        background: rgba(20, 24, 31, .42);
        transition: .2s ease;
    }

    .overlay.open {
        visibility: visible;
        opacity: 1;
    }

    .drawer {
        position: fixed;
        z-index: 1001;
        inset: 0 0 0 auto;
        width: min(560px, 100%);
        display: flex;
        flex-direction: column;
        background: #fff;
        box-shadow: -18px 0 50px rgba(20, 25, 33, .14);
        transform: translateX(102%);
        transition: transform .24s ease;
    }

    .drawer.open {
        transform: translateX(0);
    }

    .drawer-head {
        min-height: 72px;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        border-bottom: 1px solid var(--line);
    }

    .drawer-head h2 {
        margin-bottom: 2px;
        font-size: 19px;
        letter-spacing: -.03em;
    }

    .drawer-head p {
        margin-bottom: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .close-btn {
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 8px;
        color: #555c66;
        background: #f5f6f7;
        font-size: 20px;
    }

    .drawer-body {
        flex: 1;
        padding: 22px 24px 34px;
        overflow-y: auto;
    }

    .drawer-foot {
        min-height: 72px;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        border-top: 1px solid var(--line);
        background: #fff;
    }

    .form-section {
        margin-bottom: 24px;
    }

    .section-title {
        margin-bottom: 11px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 800;
    }

    .required {
        color: var(--red);
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #505762;
        font-size: 12px;
        font-weight: 720;
    }

    .form-control {
        width: 100%;
        height: 44px;
        padding: 0 13px;
        border: 1px solid var(--line-strong);
        border-radius: 9px;
        color: var(--text);
        background: #fff;
    }

    .form-help {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 11px;
    }

    .choice-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 9px;
    }

    .choice-card {
        position: relative;
        padding: 14px;
        border: 1px solid var(--line-strong);
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
    }

    .choice-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .choice-card.selected {
        border-color: var(--brand);
        box-shadow: 0 0 0 2px rgba(255, 106, 61, .1);
        background: #fffaf8;
    }

    .choice-card strong {
        display: block;
        margin-bottom: 3px;
        font-size: 13px;
    }

    .choice-card small {
        display: block;
        color: var(--muted);
        font-size: 11px;
    }

    .radio-mark {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 17px;
        height: 17px;
        border: 1.5px solid #bdc2c9;
        border-radius: 50%;
    }

    .choice-card.selected .radio-mark {
        border: 5px solid var(--brand);
    }

    .fee-types {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 6px;
    }

    .fee-type {
        height: 38px;
        border: 1px solid var(--line-strong);
        border-radius: 8px;
        color: #5a606a;
        background: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .fee-type.active {
        color: var(--brand-dark);
        border-color: var(--brand);
        background: var(--brand-soft);
    }

    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .input-unit {
        position: relative;
    }

    .input-unit .form-control {
        padding-right: 37px;
    }

    .unit {
        position: absolute;
        right: 13px;
        bottom: 12px;
        color: var(--muted);
        font-size: 12px;
    }

    .amount-range-editor {
        padding: 15px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: #fafbfc;
    }

    .amount-range-head,
    .amount-range-row {
        display: grid;
        grid-template-columns: 1fr 1fr .86fr 34px;
        gap: 7px;
        align-items: center;
    }

    .amount-range-head {
        margin-bottom: 7px;
        padding: 0 2px;
        color: var(--muted);
        font-size: 10px;
        font-weight: 750;
    }

    .amount-range-row+.amount-range-row {
        margin-top: 8px;
    }

    .range-input {
        position: relative;
    }

    .range-input input {
        width: 100%;
        height: 40px;
        padding: 0 28px 0 9px;
        border: 1px solid var(--line-strong);
        border-radius: 8px;
        background: #fff;
        font-size: 12px;
    }

    .range-input span {
        position: absolute;
        right: 9px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 10px;
    }

    .range-remove {
        width: 34px;
        height: 34px;
        border: 1px solid var(--line);
        border-radius: 8px;
        color: #7a8089;
        background: #fff;
        font-size: 17px;
    }

    .range-add {
        width: 100%;
        height: 38px;
        margin-top: 10px;
        border: 1px dashed var(--line-strong);
        border-radius: 8px;
        color: #555d68;
        background: #fff;
        font-size: 11px;
        font-weight: 750;
    }

    .range-help {
        margin: 9px 1px 0;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.55;
    }

    .toggle-row {
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-top: 1px solid var(--line);
    }

    .toggle-row:first-of-type {
        border-top: 0;
    }

    .toggle-row strong {
        display: block;
        font-size: 12px;
    }

    .toggle-row small {
        color: var(--muted);
        font-size: 10px;
    }

    .switch {
        position: relative;
        width: 40px;
        height: 23px;
        flex: none;
        border: 0;
        border-radius: 999px;
        background: #cbd0d6;
    }

    .switch::after {
        content: "";
        position: absolute;
        top: 3px;
        left: 3px;
        width: 17px;
        height: 17px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .18);
        transition: .18s ease;
    }

    .switch.on {
        background: var(--brand);
    }

    .switch.on::after {
        left: 20px;
    }

    .fee-preview {
        padding: 14px;
        border-radius: 10px;
        color: #474d56;
        background: #f5f6f8;
        font-size: 12px;
    }

    .fee-preview strong {
        color: var(--text);
    }

    .apply-product {
        margin-bottom: 18px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 13px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: #fbfbfc;
    }

    .apply-product .product-thumb {
        width: 50px;
        height: 50px;
    }

    .apply-product strong {
        display: block;
    }

    .apply-product small {
        color: var(--muted);
    }

    .select-block {
        margin-bottom: 18px;
    }

    .select-block select {
        width: 100%;
        height: 46px;
        padding: 0 12px;
        border: 1px solid var(--line-strong);
        border-radius: 9px;
        background: #fff;
    }

    .select-block select:disabled {
        color: #a0a5ad;
        background: #f1f2f4;
        cursor: not-allowed;
    }

    .branch-box {
        margin-top: 8px;
        padding: 13px 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border: 1px solid #cfe8db;
        border-radius: 10px;
        color: #286549;
        background: #f0faf5;
        font-size: 12px;
    }

    .branch-box.individual {
        color: #415f9b;
        border-color: #d8e0fa;
        background: #f2f5ff;
    }

    .apply-summary {
        padding: 16px;
        border: 1px solid var(--line);
        border-radius: 11px;
    }

    .apply-summary h3 {
        margin-bottom: 12px;
        font-size: 13px;
    }

    .summary-line {
        padding: 6px 0;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        color: var(--muted);
        font-size: 12px;
    }

    .summary-line strong {
        color: var(--text);
        text-align: right;
    }

    .product-pick-list {
        display: grid;
        gap: 8px;
    }

    .product-pick {
        min-height: 66px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 11px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
    }

    .product-pick:hover {
        border-color: #c9cdd3;
        background: #fdfdfd;
    }

    .product-pick input {
        width: 17px;
        height: 17px;
        accent-color: var(--brand);
    }

    .product-pick .product-thumb {
        width: 40px;
        height: 40px;
    }

    .product-pick-copy {
        flex: 1;
        min-width: 0;
    }

    .product-pick strong {
        display: block;
        font-size: 12px;
    }

    .product-pick small {
        display: block;
        margin-top: 2px;
        color: var(--muted);
        font-size: 10px;
    }

    .product-source {
        flex: none;
        padding: 4px 7px;
        border-radius: 999px;
        color: #4f5d73;
        background: #eef2f7;
        font-size: 9px;
        font-weight: 800;
    }

    .product-source.ungrouped {
        color: #6c551a;
        background: var(--yellow-soft);
    }

    .picker-summary {
        margin: -5px 0 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        color: var(--muted);
        font-size: 10px;
    }

    .picker-summary strong {
        color: var(--text);
    }

    .empty-picker {
        display: none;
        padding: 34px 14px;
        border: 1px dashed var(--line-strong);
        border-radius: 10px;
        color: var(--muted);
        text-align: center;
        font-size: 11px;
    }

    .method-explain {
        margin-top: 10px;
        padding: 12px 13px;
        border: 1px solid var(--line);
        border-radius: 9px;
        color: var(--muted);
        background: #fafafa;
        font-size: 11px;
    }

    .register-flow-map {
        margin-bottom: 18px;
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 8px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: #fff;
    }

    .register-flow-node {
        position: relative;
        min-height: 90px;
        padding: 14px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: #fbfbfc;
    }

    .register-flow-node:not(:last-child)::after {
        content: "›";
        position: absolute;
        z-index: 2;
        top: 50%;
        right: -14px;
        width: 20px;
        height: 20px;
        display: grid;
        place-items: center;
        border: 1px solid var(--line);
        border-radius: 50%;
        color: #a5abb4;
        background: #fff;
        transform: translateY(-50%);
    }

    .register-flow-node strong {
        display: block;
        margin: 7px 0 3px;
        font-size: 12px;
    }

    .register-flow-node small {
        display: block;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.45;
    }

    .flow-kicker {
        color: var(--brand-dark);
        font-size: 10px;
        font-weight: 850;
    }

    .register-flow-node.optional {
        border-style: dashed;
        background: #fffdf8;
    }

    .register-flow-node.result {
        border-color: #cfe8db;
        background: #f3fbf7;
    }

    .register-shell {
        display: grid;
        grid-template-columns: 218px minmax(0, 1fr);
        min-height: 620px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: #fff;
        overflow: hidden;
    }

    .register-sidebar {
        padding: 25px 20px;
        border-right: 1px solid var(--line);
        background: #fafafa;
    }

    .register-sidebar h2 {
        margin-bottom: 5px;
        font-size: 16px;
    }

    .register-sidebar>p {
        margin-bottom: 24px;
        color: var(--muted);
        font-size: 11px;
    }

    .register-steps {
        display: grid;
        gap: 0;
    }

    .register-step {
        position: relative;
        min-height: 64px;
        padding-left: 40px;
        color: var(--muted-2);
    }

    .register-step:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 31px;
        bottom: -1px;
        left: 14px;
        width: 1px;
        background: var(--line-strong);
    }

    .register-step .step-circle {
        position: absolute;
        z-index: 1;
        top: 0;
        left: 0;
        width: 29px;
        height: 29px;
        display: grid;
        place-items: center;
        border: 1px solid var(--line-strong);
        border-radius: 50%;
        color: var(--muted);
        background: #fff;
        font-size: 11px;
        font-weight: 800;
    }

    .register-step strong {
        display: block;
        padding-top: 2px;
        color: inherit;
        font-size: 12px;
    }

    .register-step small {
        display: block;
        margin-top: 2px;
        color: inherit;
        font-size: 10px;
    }

    .register-step.active {
        color: var(--text);
    }

    .register-step.active .step-circle {
        color: #fff;
        border-color: var(--brand);
        background: var(--brand);
    }

    .register-step.done {
        color: #5e6670;
    }

    .register-step.done .step-circle {
        color: var(--green);
        border-color: #b8decb;
        background: var(--green-soft);
    }

    .register-step.done:not(:last-child)::after {
        background: #b8decb;
    }

    .register-main {
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .register-screen {
        display: none;
        flex: 1;
        padding: 28px 30px 22px;
    }

    .register-screen.active {
        display: block;
    }

    .register-screen-head {
        margin-bottom: 24px;
    }

    .register-screen-head h2 {
        margin-bottom: 5px;
        font-size: 20px;
        letter-spacing: -.03em;
    }

    .register-screen-head p {
        margin-bottom: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .register-form-grid {
        display: grid;
        grid-template-columns: 160px minmax(0, 1fr);
        gap: 22px;
    }

    .upload-box {
        width: 160px;
        height: 160px;
        display: grid;
        place-items: center;
        border: 1px dashed #c6cbd2;
        border-radius: 11px;
        color: var(--muted);
        background: #fafafa;
        text-align: center;
        font-size: 11px;
        cursor: pointer;
    }

    .upload-plus {
        display: block;
        margin-bottom: 6px;
        color: #9ba1aa;
        font-size: 25px;
        line-height: 1;
    }

    .form-stack {
        display: grid;
        gap: 16px;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .condition-select-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .register-condition {
        position: relative;
        min-height: 90px;
        padding: 14px 42px 14px 14px;
        border: 1px solid var(--line-strong);
        border-radius: 11px;
        background: #fff;
        cursor: pointer;
    }

    .register-condition:hover {
        border-color: #bfc4cc;
    }

    .register-condition.selected {
        border-color: var(--brand);
        background: #fffaf8;
        box-shadow: 0 0 0 2px rgba(255, 106, 61, .08);
    }

    .register-condition strong {
        display: block;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .register-condition small {
        display: block;
        color: var(--muted);
        font-size: 11px;
    }

    .register-condition .radio-mark {
        top: 14px;
        right: 14px;
    }

    .independent-box {
        margin-top: 18px;
        padding: 16px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        border: 1px solid #dce2f6;
        border-radius: 11px;
        background: #f6f8ff;
    }

    .independent-part {
        padding-right: 10px;
    }

    .independent-part+.independent-part {
        padding-left: 14px;
        padding-right: 0;
        border-left: 1px solid #dce2f6;
    }

    .independent-part span {
        display: block;
        color: #7380a1;
        font-size: 10px;
        font-weight: 750;
    }

    .independent-part strong {
        display: block;
        margin: 3px 0;
        font-size: 12px;
    }

    .independent-part small {
        color: #6f7890;
        font-size: 10px;
    }

    .group-select-box {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--line);
    }

    .group-select-head {
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .group-select-head strong {
        display: block;
        font-size: 13px;
    }

    .group-select-head small {
        color: var(--muted);
        font-size: 10px;
    }

    .calc-result {
        margin-top: 10px;
        padding: 13px 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border: 1px solid #f0e2b0;
        border-radius: 9px;
        color: #69531a;
        background: var(--yellow-soft);
        font-size: 11px;
    }

    .calc-result.grouped {
        color: #286549;
        border-color: #cfe8db;
        background: #f0faf5;
    }

    .confirm-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .confirm-card {
        padding: 17px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: #fff;
    }

    .confirm-card h3 {
        margin-bottom: 12px;
        font-size: 13px;
    }

    .confirm-line {
        min-height: 30px;
        display: flex;
        justify-content: space-between;
        gap: 18px;
        color: var(--muted);
        font-size: 11px;
    }

    .confirm-line strong {
        color: var(--text);
        text-align: right;
        font-size: 11px;
    }

    .register-foot {
        min-height: 70px;
        padding: 13px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-top: 1px solid var(--line);
        background: #fff;
    }

    .step-hint {
        color: var(--muted);
        font-size: 11px;
    }

    .register-actions {
        display: flex;
        gap: 8px;
    }

    .completion {
        min-height: 420px;
        display: grid;
        place-items: center;
        text-align: center;
    }

    .complete-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 15px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        color: var(--green);
        background: var(--green-soft);
        font-size: 24px;
        font-weight: 900;
    }

    .completion h2 {
        margin-bottom: 7px;
        font-size: 21px;
    }

    .completion p {
        margin-bottom: 18px;
        color: var(--muted);
        font-size: 12px;
    }

    .complete-number {
        margin-bottom: 20px;
        padding: 9px 13px;
        display: inline-block;
        border-radius: 8px;
        color: #59606a;
        background: #f4f5f6;
        font-size: 11px;
    }

    .toast {
        position: fixed;
        z-index: 100;
        left: 50%;
        bottom: 28px;
        min-width: 280px;
        max-width: calc(100% - 32px);
        padding: 13px 16px;
        display: flex;
        align-items: center;
        gap: 9px;
        border-radius: 10px;
        color: #fff;
        background: #252932;
        box-shadow: var(--shadow);
        opacity: 0;
        pointer-events: none;
        transform: translate(-50%, 12px);
        transition: .22s ease;
    }

    .toast.show {
        opacity: 1;
        transform: translate(-50%, 0);
    }

    .toast-check {
        width: 18px;
        height: 18px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        color: #252932;
        background: #fff;
        font-size: 11px;
        font-weight: 900;
    }

    @media (max-width: 980px) {
        .sidebar {
            width: 190px;
        }

        .main {
            margin-left: 190px;
            padding-inline: 20px;
        }

        .guide {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .filters {
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .search-field {
            width: 180px;
        }

        .register-flow-map {
            grid-template-columns: 1fr 1fr;
        }

        .register-flow-node:not(:last-child)::after {
            display: none;
        }
    }

    @media (max-width: 760px) {
        .sidebar {
            display: none;
        }

        .main {
            margin-left: 0;
            padding: 82px 14px 46px;
        }

        .page-head {
            align-items: flex-start;
        }

        .page-head .btn {
            min-width: 40px;
            padding-inline: 11px;
        }

        .page-head .btn-label {
            display: none;
        }

        h1 {
            font-size: 23px;
        }

        .tabs {
            gap: 20px;
            overflow-x: auto;
        }

        .tab {
            flex: none;
        }

        .summary-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .summary-card {
            padding: 14px 16px;
        }

        .summary-card .value {
            font-size: 20px;
        }

        .flow {
            grid-template-columns: 1fr;
        }

        .flow-arrow {
            transform: rotate(90deg);
        }

        .card-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .filters {
            width: 100%;
            justify-content: stretch;
        }

        .search-wrap {
            flex: 1;
        }

        .search-field {
            width: 100%;
        }

        .group-grid {
            grid-template-columns: 1fr;
        }

        .register-shell {
            grid-template-columns: 1fr;
        }

        .register-sidebar {
            border-right: 0;
            border-bottom: 1px solid var(--line);
        }

        .register-sidebar>p {
            margin-bottom: 15px;
        }

        .register-steps {
            grid-template-columns: repeat(4, 1fr);
        }

        .register-step {
            min-height: 50px;
            padding: 36px 4px 0;
            text-align: center;
        }

        .register-step .step-circle {
            left: 50%;
            transform: translateX(-50%);
        }

        .register-step:not(:last-child)::after {
            top: 14px;
            right: -50%;
            bottom: auto;
            left: 50%;
            width: 100%;
            height: 1px;
        }

        .register-step small {
            display: none;
        }

        .register-screen {
            padding: 22px 18px;
        }

        .register-foot {
            padding-inline: 18px;
        }

        .condition-select-grid,
        .confirm-grid {
            grid-template-columns: 1fr;
        }

        .product-row {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .product-info {
            min-width: calc(100% - 34px);
        }

        .product-actions {
            width: 100%;
            padding-left: 30px;
            justify-content: space-between;
        }

        .product-fee {
            min-width: 80px;
        }

        .fee-types {
            grid-template-columns: repeat(2, 1fr);
        }

        .drawer-body {
            padding-inline: 18px;
        }

        .drawer-head,
        .drawer-foot {
            padding-inline: 18px;
        }
    }

    @media (max-width: 430px) {
        .guide {
            padding: 17px;
        }

        .choice-grid,
        .two-col {
            grid-template-columns: 1fr;
        }

        .register-flow-map {
            grid-template-columns: 1fr;
        }

        .register-form-grid {
            grid-template-columns: 1fr;
        }

        .upload-box {
            width: 100%;
            height: 110px;
        }

        .form-row-2,
        .independent-box {
            grid-template-columns: 1fr;
        }

        .independent-part+.independent-part {
            padding: 12px 0 0;
            border-left: 0;
            border-top: 1px solid #dce2f6;
        }

        .product-list-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .amount-range-head {
            display: none;
        }

        .amount-range-row {
            grid-template-columns: 1fr 1fr 34px;
        }

        .amount-range-row .range-input:nth-child(3) {
            grid-column: 1 / 3;
        }

        .amount-range-row .range-remove {
            grid-column: 3;
            grid-row: 1;
        }

        .step-hint {
            display: none;
        }

        .page-head p {
            font-size: 12px;
        }

        .hide-mobile {
            display: none;
        }
    }
</style>

<div class="app-shell">
    <main class="main">
        <div class="content">
            <div class="page-head">
                <div>
                    <p>배송조건으로 금액을 정하고, 배송그룹으로 묶음 여부를 별도로 관리합니다.</p>
                </div>
                <button class="btn btn-primary" id="openCreate" type="button"><span class="plus">＋</span><span class="btn-label">배송조건 추가</span></button>
            </div>

            <nav class="tabs" aria-label="배송 관리 탭">
                <button class="tab active" type="button" data-tab="conditions">배송조건 <span id="tabConditionCount">6</span></button>
                <button class="tab" type="button" data-tab="groups">묶음배송 그룹 <span id="tabGroupCount">4</span></button>
                <button class="tab" type="button" data-tab="individual">그룹 미지정 상품 <span id="ungroupedCount">3</span></button>
                <button class="tab !hidden" type="button" data-tab="register">상품 등록 Flow</button>
            </nav>

            <section class="panel active" id="panel-conditions">
                <div class="guide">
                    <div>
                        <h2>배송조건과 배송그룹은 별개의 설정입니다</h2>
                        <p>배송조건은 금액만 정합니다. 상품이 묶음배송인지 개별배송인지는 배송그룹 선택 여부로 결정합니다.</p>
                    </div>
                    <div class="flow" aria-label="상품 배송 설정 순서">
                        <div class="flow-step"><span class="step-no">1</span>
                            <div><strong>배송조건 선택</strong><small>얼마를 부과할지 · 필수</small></div>
                        </div>
                        <div class="flow-arrow">›</div>
                        <div class="flow-step"><span class="step-no">2</span>
                            <div><strong>배송그룹 선택</strong><small>묶을 상품만 선택 · 선택</small></div>
                        </div>
                        <div class="flow-arrow">›</div>
                        <div class="flow-step"><span class="step-no">3</span>
                            <div><strong>배송비 계산</strong><small>그룹 MIN/MAX 또는 개별 합산</small></div>
                        </div>
                    </div>
                </div>

                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="label">사용 중인 배송조건</div>
                        <div class="value"><span id="summaryCount">6</span>개</div>
                        <div class="sub">기본 1개 · 추가 5개</div>
                    </div>
                    <div class="summary-card">
                        <div class="label">배송조건 적용 상품</div>
                        <div class="value"><span id="appliedProductCount">134</span>개</div>
                        <div class="sub">모든 상품은 조건 1개를 필수 선택</div>
                    </div>
                    <div class="summary-card">
                        <div class="label">자동 생성 기본조건</div>
                        <div class="value">1개</div>
                        <div class="sub">기본 택배 · 필요 시 수정 가능</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-head">
                        <div>
                            <h2>배송조건 목록</h2>
                            <p>배송비 금액 규칙만 저장합니다. 묶음 여부는 상품의 배송그룹에서 정합니다.</p>
                        </div>
                        <div class="filters">
                            <div class="search-wrap"><input class="field search-field" id="conditionSearch" type="search" placeholder="조건명 검색" aria-label="배송조건 검색"></div>
                            <select class="select" id="typeFilter" aria-label="배송비 유형 필터">
                                <option value="all">전체 유형</option>
                                <option value="paid">유료</option>
                                <option value="conditional">조건부 무료</option>
                                <option value="free">무료</option>
                                <option value="quantity">수량별</option>
                                <option value="amount_range">금액 구간별</option>
                            </select>
                            <button class="btn btn-small" id="openApply" type="button">상품 적용 예시</button>
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:30%">배송조건명</th>
                                    <th>배송비 유형</th>
                                    <th>배송비 설정</th>
                                    <th>적용 상품</th>
                                    <th>상태</th>
                                    <th style="width:112px">관리</th>
                                </tr>
                            </thead>
                            <tbody id="conditionRows">
                                <tr data-type="conditional" data-name="기본 택배">
                                    <td>
                                        <div class="condition-name"><strong>기본 택배</strong><span class="badge badge-base">기본</span></div><span class="condition-desc">브랜드 입점 시 자동 생성</span>
                                    </td>
                                    <td>조건부 무료</td>
                                    <td><strong>3,000원</strong><span class="condition-desc">50,000원 이상 무료</span></td>
                                    <td><button class="usage" type="button" data-toast="기본 택배 조건을 사용하는 상품은 84개입니다.">84개</button></td>
                                    <td><span class="badge badge-bundle">사용 중</span></td>
                                    <td>
                                        <div class="row-actions"><button class="text-btn edit-condition" type="button">수정</button><button class="text-btn clone-condition" type="button">복제</button></div>
                                    </td>
                                </tr>
                                <tr data-type="amount_range" data-name="금액 구간별 배송" data-ranges='[{"min":0,"max":10000,"fee":3000},{"min":10000,"max":20000,"fee":4000},{"min":20000,"max":null,"fee":0}]'>
                                    <td>
                                        <div class="condition-name"><strong>금액 구간별 배송</strong></div><span class="condition-desc">상품 주문금액에 따라 차등 부과</span>
                                    </td>
                                    <td>금액 구간별</td>
                                    <td><strong>구간 3개</strong><span class="condition-desc">20,000원 이상 무료</span></td>
                                    <td><button class="usage" type="button" data-toast="금액 구간별 배송 조건을 사용하는 상품은 6개입니다.">6개</button></td>
                                    <td><span class="badge badge-bundle">사용 중</span></td>
                                    <td>
                                        <div class="row-actions"><button class="text-btn edit-condition" type="button">수정</button><button class="text-btn clone-condition" type="button">복제</button></div>
                                    </td>
                                </tr>
                                <tr data-type="free" data-name="무료배송">
                                    <td>
                                        <div class="condition-name"><strong>무료배송</strong></div><span class="condition-desc">프로모션 상품 공통</span>
                                    </td>
                                    <td>무료</td>
                                    <td><strong>0원</strong></td>
                                    <td><button class="usage" type="button" data-toast="무료배송 조건을 사용하는 상품은 12개입니다.">12개</button></td>
                                    <td><span class="badge badge-bundle">사용 중</span></td>
                                    <td>
                                        <div class="row-actions"><button class="text-btn edit-condition" type="button">수정</button><button class="text-btn clone-condition" type="button">복제</button></div>
                                    </td>
                                </tr>
                                <tr data-type="paid" data-name="냉장 기본">
                                    <td>
                                        <div class="condition-name"><strong>냉장 기본</strong></div><span class="condition-desc">냉장 상품에 자주 사용</span>
                                    </td>
                                    <td>유료</td>
                                    <td><strong>4,000원</strong><span class="condition-desc">제주 +3,000원</span></td>
                                    <td><button class="usage" type="button" data-toast="냉장 기본 조건을 사용하는 상품은 20개입니다.">20개</button></td>
                                    <td><span class="badge badge-bundle">사용 중</span></td>
                                    <td>
                                        <div class="row-actions"><button class="text-btn edit-condition" type="button">수정</button><button class="text-btn clone-condition" type="button">복제</button></div>
                                    </td>
                                </tr>
                                <tr data-type="paid" data-name="대형배송">
                                    <td>
                                        <div class="condition-name"><strong>대형배송</strong></div><span class="condition-desc">대형 상품 배송비 규칙</span>
                                    </td>
                                    <td>유료</td>
                                    <td><strong>12,000원</strong><span class="condition-desc">주문 1건당 부과</span></td>
                                    <td><button class="usage" type="button" data-toast="대형배송 조건을 사용하는 상품은 5개입니다.">5개</button></td>
                                    <td><span class="badge badge-bundle">사용 중</span></td>
                                    <td>
                                        <div class="row-actions"><button class="text-btn edit-condition" type="button">수정</button><button class="text-btn clone-condition" type="button">복제</button></div>
                                    </td>
                                </tr>
                                <tr data-type="quantity" data-name="수량별 배송">
                                    <td>
                                        <div class="condition-name"><strong>수량별 배송</strong></div><span class="condition-desc">2개 단위로 반복 부과</span>
                                    </td>
                                    <td>수량별</td>
                                    <td><strong>3,500원</strong><span class="condition-desc">2개마다 반복</span></td>
                                    <td><button class="usage" type="button" data-toast="수량별 배송 조건을 사용하는 상품은 7개입니다.">7개</button></td>
                                    <td><span class="badge badge-bundle">사용 중</span></td>
                                    <td>
                                        <div class="row-actions"><button class="text-btn edit-condition" type="button">수정</button><button class="text-btn clone-condition" type="button">복제</button></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="empty-search" id="emptySearch">검색 조건에 맞는 배송조건이 없습니다.</div>
                    </div>
                    <div class="table-foot">
                        <span>총 <strong id="footerCount">6</strong>개</span>
                        <div class="pagination"><button class="page-btn" type="button">‹</button><button class="page-btn active" type="button">1</button><button class="page-btn" type="button">›</button></div>
                    </div>
                </div>

                <div class="rule-note"><span class="info-dot">i</span><span><strong>운영 기준:</strong> 배송조건에는 묶음·개별 구분을 저장하지 않습니다. 사용 중인 조건을 수정하면 연결된 상품 전체에 반영되므로, 일부 상품만 다르면 조건을 복제해서 사용하세요.</span></div>
            </section>

            <section class="panel" id="panel-groups">
                <div class="guide">
                    <div>
                        <h2>묶음배송이 필요한 경우에만 그룹을 선택합니다</h2>
                        <p>배송그룹은 자동 생성되지 않습니다. 필요한 그룹을 만들고 MIN/MAX를 정한 뒤, 묶을 상품에만 선택합니다.</p>
                    </div>
                    <div class="flow" aria-label="묶음배송 그룹 운영 순서">
                        <div class="flow-step"><span class="step-no">1</span>
                            <div><strong>그룹 생성</strong><small>합포장 가능한 범위 정의</small></div>
                        </div>
                        <div class="flow-arrow">›</div>
                        <div class="flow-step"><span class="step-no">2</span>
                            <div><strong>MIN · MAX 선택</strong><small>그룹 배송비 계산 기준</small></div>
                        </div>
                        <div class="flow-arrow">›</div>
                        <div class="flow-step"><span class="step-no">3</span>
                            <div><strong>상품에서 그룹 선택</strong><small>묶음배송 상품만 선택</small></div>
                        </div>
                    </div>
                </div>
                <div class="card" style="padding:20px">
                    <div class="card-head" style="padding:0 0 17px; min-height:auto; margin-bottom:15px">
                        <div>
                            <h2>묶음배송 그룹</h2>
                            <p>실제로 함께 포장·출고할 수 있는 상품끼리 묶습니다.</p>
                        </div>
                        <button class="btn btn-small" id="openGroupCreate" type="button">＋ 그룹 추가</button>
                    </div>
                    <div class="group-grid" id="groupGrid">
                        <article class="group-card" data-group-name="일반상품 묶음그룹">
                            <div class="group-top">
                                <div>
                                    <h3>일반상품 묶음그룹</h3>
                                    <p>관리자가 생성한 일반 택배 묶음그룹</p>
                                </div><span class="badge badge-bundle">사용 중</span>
                            </div>
                            <div class="group-meta">
                                <div><span>상품 수</span><strong class="group-product-count">76개</strong></div>
                                <div><span>계산 방식</span><strong>MAX</strong></div>
                                <div><span>적용 조건</span><strong>2개</strong></div>
                            </div><button class="btn btn-small group-products" type="button" style="width:100%; margin-top:14px">기존 상품 추가·이동</button>
                        </article>
                        <article class="group-card" data-group-name="냉장배송 A">
                            <div class="group-top">
                                <div>
                                    <h3>냉장배송 A</h3>
                                    <p>용인 물류센터 냉장 합포장 상품</p>
                                </div><span class="badge badge-bundle">사용 중</span>
                            </div>
                            <div class="group-meta">
                                <div><span>상품 수</span><strong class="group-product-count">15개</strong></div>
                                <div><span>계산 방식</span><strong>MAX</strong></div>
                                <div><span>적용 조건</span><strong>1개</strong></div>
                            </div><button class="btn btn-small group-products" type="button" style="width:100%; margin-top:14px">기존 상품 추가·이동</button>
                        </article>
                        <article class="group-card" data-group-name="냉장배송 B">
                            <div class="group-top">
                                <div>
                                    <h3>냉장배송 B</h3>
                                    <p>성남 물류센터 냉장 합포장 상품</p>
                                </div><span class="badge badge-bundle">사용 중</span>
                            </div>
                            <div class="group-meta">
                                <div><span>상품 수</span><strong class="group-product-count">13개</strong></div>
                                <div><span>계산 방식</span><strong>MAX</strong></div>
                                <div><span>적용 조건</span><strong>1개</strong></div>
                            </div><button class="btn btn-small group-products" type="button" style="width:100%; margin-top:14px">기존 상품 추가·이동</button>
                        </article>
                        <article class="group-card" data-group-name="냉장배송 C">
                            <div class="group-top">
                                <div>
                                    <h3>냉장배송 C</h3>
                                    <p>두 상품끼리만 합포장 가능한 그룹</p>
                                </div><span class="badge badge-bundle">사용 중</span>
                            </div>
                            <div class="group-meta">
                                <div><span>상품 수</span><strong class="group-product-count">2개</strong></div>
                                <div><span>계산 방식</span><strong>MAX</strong></div>
                                <div><span>적용 조건</span><strong>1개</strong></div>
                            </div><button class="btn btn-small group-products" type="button" style="width:100%; margin-top:14px">기존 상품 추가·이동</button>
                        </article>
                    </div>
                </div>
                <div class="rule-note"><span class="info-dot">i</span><span><strong>운영 기준:</strong> 상품 등록 시 배송그룹을 선택하지 않으면 자동으로 개별배송이 됩니다. 기존 상품을 묶음배송으로 바꿀 때만 ‘기존 상품 추가·이동’을 사용합니다.</span></div>
            </section>

            <section class="panel" id="panel-individual">
                <div class="card" style="padding:20px">
                    <div class="card-head" style="padding:0 0 17px; min-height:auto; margin-bottom:15px">
                        <div>
                            <h2>그룹 미지정 상품</h2>
                            <p>배송조건은 적용되어 있지만 배송그룹을 선택하지 않아, 배송비가 상품별로 부과되는 상품입니다.</p>
                        </div>
                        <button class="btn btn-small" id="openApplySecond" type="button" disabled>선택 상품 배송설정 (0)</button>
                    </div>
                    <div class="product-list-toolbar">
                        <label><input id="selectAllUngrouped" type="checkbox"> 전체 선택</label>
                        <span>상품 행을 클릭하거나 ‘배송설정’을 눌러 개별 변경</span>
                    </div>
                    <div class="product-list" id="ungroupedProductList">
                        <div class="product-row" tabindex="0" data-product-name="대용량 보냉 컨테이너 48L" data-product-no="ND-20481" data-condition="대형배송" data-thumb="▣">
                            <input class="row-check" type="checkbox" aria-label="대용량 보냉 컨테이너 48L 선택">
                            <div class="product-info">
                                <div class="product-thumb">▣</div>
                                <div><strong>대용량 보냉 컨테이너 48L</strong><small>ND-20481 · 대형배송 · 그룹 미지정</small></div>
                            </div>
                            <div class="product-actions">
                                <div class="product-fee"><strong>12,000원</strong><small>개별 부과</small></div><button class="btn btn-small row-shipping-btn" type="button">배송설정</button>
                            </div>
                        </div>
                        <div class="product-row" tabindex="0" data-product-name="프리미엄 유리 화병 세트" data-product-no="ND-20117" data-condition="수량별 배송" data-thumb="●">
                            <input class="row-check" type="checkbox" aria-label="프리미엄 유리 화병 세트 선택">
                            <div class="product-info">
                                <div class="product-thumb">●</div>
                                <div><strong>프리미엄 유리 화병 세트</strong><small>ND-20117 · 수량별 배송 · 그룹 미지정</small></div>
                            </div>
                            <div class="product-actions">
                                <div class="product-fee"><strong>3,500원</strong><small>2개마다 반복</small></div><button class="btn btn-small row-shipping-btn" type="button">배송설정</button>
                            </div>
                        </div>
                        <div class="product-row" tabindex="0" data-product-name="원목 사이드 테이블" data-product-no="ND-19802" data-condition="대형배송" data-thumb="◇">
                            <input class="row-check" type="checkbox" aria-label="원목 사이드 테이블 선택">
                            <div class="product-info">
                                <div class="product-thumb">◇</div>
                                <div><strong>원목 사이드 테이블</strong><small>ND-19802 · 대형배송 · 그룹 미지정</small></div>
                            </div>
                            <div class="product-actions">
                                <div class="product-fee"><strong>12,000원</strong><small>개별 부과</small></div><button class="btn btn-small row-shipping-btn" type="button">배송설정</button>
                            </div>
                        </div>
                    </div>
                    <div class="empty-products" id="emptyUngrouped">그룹 미지정 상품이 없습니다.</div>
                </div>
            </section>

            <section class="panel" id="panel-register">
                <div class="register-flow-map" aria-label="상품 등록 전체 흐름">
                    <div class="register-flow-node"><span class="flow-kicker">STEP 1</span><strong>상품 기본정보</strong><small>상품명·카테고리·판매가·재고 입력</small></div>
                    <div class="register-flow-node"><span class="flow-kicker">STEP 2 · 필수</span><strong>배송조건 선택</strong><small>등록된 배송조건 6개 중 하나 선택</small></div>
                    <div class="register-flow-node optional"><span class="flow-kicker">STEP 3 · 선택</span><strong>배송그룹 선택</strong><small>미선택 시 자동으로 개별배송</small></div>
                    <div class="register-flow-node"><span class="flow-kicker">STEP 4</span><strong>계산 결과 확인</strong><small>그룹 선택 시 해당 MIN·MAX 적용</small></div>
                    <div class="register-flow-node result"><span class="flow-kicker">DONE</span><strong>상품 등록 완료</strong><small>배송조건과 그룹을 각각 저장</small></div>
                </div>

                <div class="rule-note" style="margin:0 0 18px"><span class="info-dot">i</span><span><strong>등록 기준:</strong> 배송조건은 반드시 1개를 선택합니다. 배송그룹을 선택하면 묶음배송, 선택하지 않으면 개별배송으로 저장됩니다.</span></div>

                <div class="register-shell">
                    <aside class="register-sidebar">
                        <h2>신규 상품 등록</h2>
                        <p>등록 과정을 직접 눌러보세요.</p>
                        <div class="register-steps">
                            <div class="register-step active" data-reg-step="1"><span class="step-circle">1</span><strong>기본정보</strong><small>상품 정보를 입력합니다.</small></div>
                            <div class="register-step" data-reg-step="2"><span class="step-circle">2</span><strong>배송설정</strong><small>조건과 그룹을 선택합니다.</small></div>
                            <div class="register-step" data-reg-step="3"><span class="step-circle">3</span><strong>최종확인</strong><small>등록 내용을 확인합니다.</small></div>
                            <div class="register-step" data-reg-step="4"><span class="step-circle">4</span><strong>등록완료</strong><small>상품 등록이 완료됩니다.</small></div>
                        </div>
                    </aside>

                    <div class="register-main">
                        <div class="register-screen active" data-reg-screen="1">
                            <div class="register-screen-head">
                                <h2>상품 기본정보</h2>
                                <p>판매에 필요한 기본 정보를 입력해주세요.</p>
                            </div>
                            <div class="register-form-grid">
                                <button class="upload-box" id="mockUpload" type="button"><span><span class="upload-plus">＋</span>대표 이미지 등록<br><small>권장 1,000 × 1,000px</small></span></button>
                                <div class="form-stack">
                                    <div><label class="form-label" for="regProductName">상품명 <span class="required">*</span></label><input class="form-control" id="regProductName" type="text" value="고소한 피스타치오 크림 200g"></div>
                                    <div><label class="form-label" for="regCategory">카테고리 <span class="required">*</span></label><select class="form-control" id="regCategory">
                                            <option>식품 &gt; 가공식품 &gt; 잼·스프레드</option>
                                            <option>식품 &gt; 과자·간식</option>
                                            <option>생활 &gt; 주방용품</option>
                                        </select></div>
                                    <div class="form-row-2">
                                        <div class="input-unit"><label class="form-label" for="regPrice">판매가 <span class="required">*</span></label><input class="form-control" id="regPrice" type="number" min="100" step="100" value="18900"><span class="unit">원</span></div>
                                        <div class="input-unit"><label class="form-label" for="regStock">재고수량 <span class="required">*</span></label><input class="form-control" id="regStock" type="number" min="0" value="120"><span class="unit">개</span></div>
                                    </div>
                                    <div><label class="form-label" for="regStatus">판매 상태</label><select class="form-control" id="regStatus">
                                            <option>판매중</option>
                                            <option>판매대기</option>
                                        </select></div>
                                </div>
                            </div>
                        </div>

                        <div class="register-screen" data-reg-screen="2">
                            <div class="register-screen-head">
                                <h2>배송설정</h2>
                                <p>배송조건으로 금액을 정하고, 배송그룹 선택 여부로 묶음·개별을 결정합니다.</p>
                            </div>
                            <div class="section-title">배송조건 <span class="required">*</span></div>
                            <div class="condition-select-grid" id="registerConditions">
                                <button class="register-condition selected" type="button" data-condition="기본 택배" data-fee="3,000원" data-rule="50,000원 이상 무료"><span class="radio-mark"></span><strong>기본 택배 <span class="badge badge-base">기본</span></strong><small>3,000원 · 50,000원 이상 구매 시 무료</small></button>
                                <button class="register-condition" type="button" data-condition="금액 구간별 배송" data-fee="구간 3개" data-rule="20,000원 이상 무료"><span class="radio-mark"></span><strong>금액 구간별 배송</strong><small>0~1만원 3,000원 · 1~2만원 4,000원 · 2만원 이상 무료</small></button>
                                <button class="register-condition" type="button" data-condition="무료배송" data-fee="0원" data-rule="주문금액과 관계없이 무료"><span class="radio-mark"></span><strong>무료배송</strong><small>주문금액과 관계없이 배송비 무료</small></button>
                                <button class="register-condition" type="button" data-condition="냉장 기본" data-fee="4,000원" data-rule="제주 지역 3,000원 추가"><span class="radio-mark"></span><strong>냉장 기본</strong><small>4,000원 · 제주 지역 3,000원 추가</small></button>
                                <button class="register-condition" type="button" data-condition="대형배송" data-fee="12,000원" data-rule="주문 1건당 12,000원"><span class="radio-mark"></span><strong>대형배송</strong><small>12,000원 · 그룹 미선택 시 개별 부과</small></button>
                                <button class="register-condition" type="button" data-condition="수량별 배송" data-fee="3,500원" data-rule="상품 2개마다 반복 부과"><span class="radio-mark"></span><strong>수량별 배송</strong><small>3,500원 · 상품 2개마다 반복 부과</small></button>
                            </div>

                            <div class="independent-box">
                                <div class="independent-part"><span>배송조건 · 필수</span><strong>얼마를 부과할지 결정</strong><small>고정·무료·조건부·수량별·금액 구간별 정책</small></div>
                                <div class="independent-part"><span>배송그룹 · 선택</span><strong>묶음 또는 개별을 결정</strong><small>선택하면 MIN/MAX 묶음, 미선택이면 개별 합산</small></div>
                            </div>

                            <div class="group-select-box">
                                <div class="group-select-head">
                                    <div><strong>묶음배송 그룹</strong><small>필요한 경우에만 선택합니다.</small></div><span class="badge badge-warning">선택사항</span>
                                </div>
                                <select class="form-control" id="regGroup">
                                    <option value="none|선택 안 함|개별배송 · 상품별 배송비 합산">선택 안 함 — 개별배송</option>
                                    <option value="MAX|일반상품 묶음그룹|그룹 내 최고 배송비 1회">일반상품 묶음그룹 — MAX</option>
                                    <option value="MAX|냉장배송 A|그룹 내 최고 배송비 1회">냉장배송 A — MAX</option>
                                    <option value="MAX|냉장배송 B|그룹 내 최고 배송비 1회">냉장배송 B — MAX</option>
                                    <option value="MIN|냉장배송 C|그룹 내 최저 배송비 1회">냉장배송 C — MIN</option>
                                </select>
                                <div class="calc-result" id="regCalcResult"><span class="info-dot">i</span><span><strong>개별배송으로 등록됩니다.</strong><br>배송그룹을 선택하지 않았으므로 이 상품의 배송비가 다른 상품과 별도로 합산됩니다.</span></div>
                            </div>
                        </div>

                        <div class="register-screen" data-reg-screen="3">
                            <div class="register-screen-head">
                                <h2>최종 확인</h2>
                                <p>상품과 배송설정이 올바른지 확인해주세요.</p>
                            </div>
                            <div class="confirm-grid">
                                <div class="confirm-card">
                                    <h3>상품정보</h3>
                                    <div class="confirm-line"><span>상품명</span><strong id="confirmName">고소한 피스타치오 크림 200g</strong></div>
                                    <div class="confirm-line"><span>카테고리</span><strong id="confirmCategory">식품 &gt; 가공식품 &gt; 잼·스프레드</strong></div>
                                    <div class="confirm-line"><span>판매가</span><strong id="confirmPrice">18,900원</strong></div>
                                    <div class="confirm-line"><span>재고</span><strong id="confirmStock">120개</strong></div>
                                </div>
                                <div class="confirm-card">
                                    <h3>배송정보</h3>
                                    <div class="confirm-line"><span>배송조건</span><strong id="confirmCondition">기본 택배</strong></div>
                                    <div class="confirm-line"><span>배송비 규칙</span><strong id="confirmFee">3,000원</strong></div>
                                    <div class="confirm-line"><span>배송방식</span><strong id="confirmGroup">개별배송</strong></div>
                                    <div class="confirm-line"><span>계산 방식</span><strong id="confirmCalc">상품별 배송비 합산</strong></div>
                                </div>
                            </div>
                            <div class="branch-box" id="confirmMessage" style="margin-top:14px"><span class="info-dot">i</span><span>배송그룹을 선택하지 않아 개별배송으로 등록되며, 다른 상품과 주문 시 배송비가 합산됩니다.</span></div>
                        </div>

                        <div class="register-screen" data-reg-screen="4">
                            <div class="completion">
                                <div>
                                    <div class="complete-icon">✓</div>
                                    <h2>상품 등록이 완료되었습니다</h2>
                                    <p id="completeProductName">고소한 피스타치오 크림 200g</p>
                                    <div class="complete-number" id="completeProductNumber">상품번호 ND-21408</div>
                                    <div class="method-explain" id="completeDestination" style="max-width:360px; margin:16px auto 18px">등록한 상품이 배송 관리에 반영되었습니다.</div><button class="btn btn-primary" id="resetRegister" type="button">새 상품 등록하기</button>
                                </div>
                            </div>
                        </div>

                        <div class="register-foot" id="registerFoot">
                            <span class="step-hint" id="regStepHint">필수 정보를 입력한 뒤 다음 단계로 이동하세요.</span>
                            <div class="register-actions"><button class="btn" id="regPrev" type="button" style="visibility:hidden">이전</button><button class="btn btn-brand" id="regNext" type="button">배송설정으로</button></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<div class="overlay" id="overlay"></div>

<!-- 배송 조건 추가 Drawer -->
<aside class="drawer" id="conditionDrawer" role="dialog" aria-modal="true" aria-labelledby="drawerTitle">
    <div class="drawer-head">
        <div>
            <h2 id="drawerTitle">배송조건 추가</h2>
            <p>자주 사용하는 배송비 규칙을 저장합니다.</p>
        </div>
        <button class="close-btn" type="button" data-close aria-label="닫기">×</button>
    </div>

    <form name="fdeliverycondition" id="fdeliverycondition" method="post" autocomplete="off">
        <div class="drawer-body">
            <div class="form-section">
                <label class="form-label" for="conditionName">배송조건명</label>
                <input class="form-control" id="conditionName" name="dc_name" type="text" maxlength="30" placeholder="예: 냉동 기본배송">
                <p class="form-help">상품 등록 시 알아보기 쉬운 이름으로 입력하세요.</p>
            </div>

            <div class="form-section">
                <div class="section-title">배송비 유형</div>
                <input type="hidden" name="dc_type" id="dc_type" value="conditional">
                <div class="fee-types" id="feeTypes">
                    <button class="fee-type" type="button" data-fee="paid">유료</button>
                    <button class="fee-type active" type="button" data-fee="conditional">조건부 무료</button>
                    <button class="fee-type" type="button" data-fee="free">무료</button>
                    <button class="fee-type" type="button" data-fee="quantity">수량별</button>
                    <button class="fee-type" type="button" data-fee="amount_range">금액 구간별</button>
                </div>
            </div>

            <div class="form-section" id="feeFields">
                <div class="two-col">
                    <div class="input-unit">
                        <label class="form-label" for="baseFee">기본 배송비</label>
                        <input class="form-control" id="baseFee" name="dc_price" type="number" min="0" step="100" value="3000">
                        <span class="unit">원</span>
                    </div>

                    <div class="input-unit" id="thresholdWrap">
                        <label class="form-label" for="freeThreshold">무료배송 기준</label>
                        <input class="form-control" id="freeThreshold" name="dc_minimum" type="number" min="0" step="1000" value="50000">
                        <span class="unit">원</span>
                    </div>

                    <div class="input-unit" id="quantityWrap" style="display:none">
                        <label class="form-label" for="repeatQuantity">반복 부과 수량</label>
                        <input class="form-control" id="repeatQuantity" name="dc_qty" type="number" min="1" value="1">
                        <span class="unit">개</span>
                    </div>
                </div>
            </div>

            <div class="form-section" id="amountRangeFields" style="display:none">
                <div class="section-title"><span>주문금액별 배송비 구간</span><span class="badge badge-warning">이상–미만</span></div>
                <div class="amount-range-editor">
                    <div class="amount-range-head" aria-hidden="true">
                        <span>시작 금액 · 이상</span>
                        <span>종료 금액 · 미만</span>
                        <span>배송비</span>
                        <span></span>
                    </div>

                    <div id="amountRangeRows">
                        <div class="amount-range-row">
                            <label class="range-input">
                                <input class="range-min" name="dc_range_min[]" type="number" min="0" step="1000" value="0" aria-label="시작 금액">
                                <span>원</span>
                            </label>

                            <label class="range-input">
                                <input class="range-max" name="dc_range_max[]" type="number" min="0" step="1000" value="10000" aria-label="종료 금액">
                                <span>원</span>
                            </label>

                            <label class="range-input">
                                <input class="range-fee" name="dc_range_price[]" type="number" min="0" step="100" value="3000" aria-label="배송비">
                                <span>원</span>
                            </label>

                            <button class="range-remove" type="button" aria-label="구간 삭제">×</button>
                        </div>

                        <div class="amount-range-row">
                            <label class="range-input">
                                <input class="range-min" name="dc_range_min[]" type="number" min="0" step="1000" value="10000" aria-label="시작 금액">
                                <span>원</span>
                            </label>

                            <label class="range-input">
                                <input class="range-max" name="dc_range_max[]" type="number" min="0" step="1000" value="20000" aria-label="종료 금액">
                                <span>원</span>
                            </label>

                            <label class="range-input">
                                <input class="range-fee" name="dc_range_price[]" type="number" min="0" step="100" value="4000" aria-label="배송비">
                                <span>원</span>
                            </label>

                            <button class="range-remove" type="button" aria-label="구간 삭제">×</button>
                        </div>

                        <div class="amount-range-row">
                            <label class="range-input">
                                <input class="range-min" name="dc_range_min[]" type="number" min="0" step="1000" value="20000" aria-label="시작 금액">
                                <span>원</span>
                            </label>

                            <label class="range-input">
                                <input class="range-max" name="dc_range_max[]" type="number" min="0" step="1000" placeholder="제한 없음" aria-label="종료 금액">
                                <span>원</span>
                            </label>

                            <label class="range-input">
                                <input class="range-fee" name="dc_range_price[]" type="number" min="0" step="100" value="0" aria-label="배송비">
                                <span>원</span>
                            </label>

                            <button class="range-remove" type="button" aria-label="구간 삭제">×</button>
                        </div>
                    </div>

                    <button class="range-add" id="addAmountRange" type="button">＋ 구간 추가</button>
                    <p class="range-help">각 상품의 주문금액(판매가 × 수량)을 기준으로 계산합니다. 종료 금액을 비우면 해당 시작 금액 이상 전체에 적용되며, 배송비 0원은 무료로 표시됩니다.</p>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">지역별 추가 배송비</div>
                <div class="toggle-row">
                    <div><strong>제주 지역 추가비</strong><small>기본 배송비에 3,000원 추가</small></div><button class="switch on" type="button" data-switch aria-label="제주 지역 추가비 사용"></button>
                </div>
                <div class="toggle-row">
                    <div><strong>제주 외 도서산간 추가비</strong><small>기본 배송비에 5,000원 추가</small></div><button class="switch on" type="button" data-switch aria-label="도서산간 추가비 사용"></button>
                </div>
            </div>

            <div class="fee-preview" id="feePreview"><strong>미리보기</strong><br>주문금액 50,000원 미만이면 3,000원, 이상이면 무료로 적용됩니다.</div>
        </div>

        <div class="drawer-foot">
            <button class="btn" type="button" data-close>취소</button>
            <button type="submit" id="saveCondition" class="btn btn-brand">저장</button>
        </div>
    </form>
</aside>

<aside class="drawer" id="applyDrawer" role="dialog" aria-modal="true" aria-labelledby="applyTitle">
    <div class="drawer-head">
        <div>
            <h2 id="applyTitle">배송조건·배송그룹 변경 예시</h2>
            <p>상품에 적용된 배송비 조건과 묶음배송 여부를 변경합니다.</p>
        </div>
        <button class="close-btn" type="button" data-close aria-label="닫기">×</button>
    </div>
    <div class="drawer-body">
        <div class="apply-product">
            <div class="product-thumb" id="applyProductThumb">▣</div>
            <div><strong id="applyProductName">대용량 보냉 컨테이너 48L</strong><small id="applyProductMeta">상품번호 ND-20481 · 현재 배송조건 대형배송</small></div>
        </div>

        <div class="select-block">
            <label class="form-label" for="applyCondition">배송조건 <span class="required">*</span></label>
            <select id="applyCondition">
                <option value="기본 택배|조건부 무료 · 3,000원">기본 택배 — 조건부 무료 3,000원</option>
                <option value="금액 구간별 배송|금액 구간별 · 구간 3개">금액 구간별 배송 — 20,000원 이상 무료</option>
                <option value="무료배송|무료 · 0원">무료배송 — 배송비 0원</option>
                <option value="냉장 기본|유료 · 4,000원">냉장 기본 — 유료 4,000원</option>
                <option value="대형배송|유료 · 12,000원">대형배송 — 유료 12,000원</option>
                <option value="수량별 배송|2개마다 · 3,500원">수량별 배송 — 2개마다 3,500원</option>
            </select>
            <p class="form-help">등록된 조건 중 하나를 반드시 선택합니다.</p>
        </div>

        <div class="select-block">
            <label class="form-label" for="applyGroup">묶음배송 그룹 <span class="badge badge-warning">선택사항</span></label>
            <select id="applyGroup">
                <option value="none">선택 안 함 · 개별배송</option>
                <option value="일반상품 묶음그룹">일반상품 묶음그룹 · MAX</option>
                <option value="냉장배송 A">냉장배송 A · MAX</option>
                <option value="냉장배송 B">냉장배송 B · MAX</option>
                <option value="냉장배송 C">냉장배송 C · MAX</option>
            </select>
            <p class="form-help" id="groupHelp">선택하지 않으면 개별배송, 선택하면 해당 그룹의 묶음배송으로 등록됩니다.</p>
        </div>

        <div class="branch-box individual" id="branchBox"><span class="info-dot">i</span><span><strong>개별배송으로 등록됩니다.</strong><br>배송그룹을 선택하지 않아 상품별 배송비가 합산됩니다.</span></div>

        <div class="apply-summary" style="margin-top:18px">
            <h3>적용 결과</h3>
            <div class="summary-line"><span>배송조건</span><strong id="summaryCondition">기본 택배</strong></div>
            <div class="summary-line"><span>배송비 규칙</span><strong id="summaryFee">조건부 무료 · 3,000원</strong></div>
            <div class="summary-line"><span>배송방식</span><strong id="summaryGroup">개별배송</strong></div>
        </div>
    </div>
    <div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" id="applySave" type="button">상품에 적용</button></div>
</aside>

<aside class="drawer" id="groupDrawer" role="dialog" aria-modal="true" aria-labelledby="groupDrawerTitle">
    <div class="drawer-head">
        <div>
            <h2 id="groupDrawerTitle">묶음배송 그룹 추가</h2>
            <p>상품을 넣기 전에 그룹의 계산 기준을 먼저 정합니다.</p>
        </div>
        <button class="close-btn" type="button" data-close aria-label="닫기">×</button>
    </div>
    <div class="drawer-body">
        <div class="form-section">
            <label class="form-label" for="groupName">그룹명 <span class="required">*</span></label>
            <input class="form-control" id="groupName" type="text" maxlength="30" placeholder="예: 냉동배송 A">
            <p class="form-help">함께 포장·출고할 수 있는 상품 범위를 알아보기 쉽게 입력하세요.</p>
        </div>

        <div class="form-section">
            <div class="section-title">배송비 계산 방식 <span class="required">*</span></div>
            <div class="choice-grid" id="calcChoices">
                <label class="choice-card" data-calc="MIN"><input type="radio" name="calculation" value="MIN"><span class="radio-mark"></span><strong>MIN · 최저 배송비</strong><small>그룹 상품 중 가장 낮은 배송비를 1회 부과</small></label>
                <label class="choice-card selected" data-calc="MAX"><input type="radio" name="calculation" value="MAX" checked><span class="radio-mark"></span><strong>MAX · 최고 배송비</strong><small>그룹 상품 중 가장 높은 배송비를 1회 부과</small></label>
            </div>
            <div class="method-explain" id="calcExplain"><strong>MAX 예시</strong> · 같은 그룹에서 배송비 3,000원과 4,000원 상품을 함께 구매하면 4,000원을 한 번 부과합니다.</div>
        </div>

        <div class="form-section">
            <div class="section-title">그룹 생성 후 상품 연결</div>
            <div class="branch-box"><span class="info-dot">i</span><span><strong>신규 상품</strong>은 상품 등록 화면에서 이 그룹을 선택합니다.<br><strong>기존 상품</strong>은 그룹 저장 후 ‘기존 상품 추가·이동’에서 일괄 처리할 수 있습니다.</span></div>
        </div>
    </div>
    <div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" id="saveGroup" type="button">그룹 저장</button></div>
</aside>

<aside class="drawer" id="productGroupDrawer" role="dialog" aria-modal="true" aria-labelledby="productGroupTitle">
    <div class="drawer-head">
        <div>
            <h2 id="productGroupTitle">기존 상품 추가·이동</h2>
            <p id="productGroupSub">선택한 상품의 배송그룹을 일괄 변경합니다.</p>
        </div>
        <button class="close-btn" type="button" data-close aria-label="닫기">×</button>
    </div>
    <div class="drawer-body">
        <div class="rule-note" style="margin:0 0 16px"><span class="info-dot">i</span><span><strong>이동 가능한 상품만 표시합니다.</strong><br>선택한 대상 그룹의 기존 상품은 제외하고, 다른 그룹 상품과 그룹 미지정 상품을 보여줍니다.</span></div>
        <div class="two-col select-block">
            <div><label class="form-label" for="productPickSearch">상품 검색</label><input class="form-control" id="productPickSearch" type="search" placeholder="상품명 또는 상품번호"></div>
            <div><label class="form-label" for="productSourceFilter">현재 소속</label><select class="form-control" id="productSourceFilter">
                    <option value="all">다른 그룹 + 미지정 전체</option>
                </select></div>
        </div>
        <div class="picker-summary"><span><strong id="productPickCount">0</strong>개 상품 표시</span><span id="productPickTarget">대상 그룹 기존 상품 제외</span></div>
        <div class="product-pick-list" id="productPickList">
            <label class="product-pick" data-product-name="말차 크림 쿠키 세트" data-product-no="ND-21004" data-current-group="일반상품 묶음그룹"><input type="checkbox"><span class="product-thumb">●</span><span class="product-pick-copy"><strong>말차 크림 쿠키 세트</strong><small>ND-21004 · 기본 택배</small></span><span class="product-source">일반상품 묶음그룹</span></label>
            <label class="product-pick" data-product-name="수제 버터 샌드 8입" data-product-no="ND-20872" data-current-group="일반상품 묶음그룹"><input type="checkbox"><span class="product-thumb">▣</span><span class="product-pick-copy"><strong>수제 버터 샌드 8입</strong><small>ND-20872 · 기본 택배</small></span><span class="product-source">일반상품 묶음그룹</span></label>
            <label class="product-pick" data-product-name="딸기 생크림 케이크" data-product-no="ND-20931" data-current-group="냉장배송 A"><input type="checkbox"><span class="product-thumb">□</span><span class="product-pick-copy"><strong>딸기 생크림 케이크</strong><small>ND-20931 · 냉장 기본</small></span><span class="product-source">냉장배송 A</span></label>
            <label class="product-pick" data-product-name="블루베리 요거트 6입" data-product-no="ND-20773" data-current-group="냉장배송 B"><input type="checkbox"><span class="product-thumb">◇</span><span class="product-pick-copy"><strong>블루베리 요거트 6입</strong><small>ND-20773 · 냉장 기본</small></span><span class="product-source">냉장배송 B</span></label>
            <label class="product-pick" data-product-name="미니 치즈케이크 2입" data-product-no="ND-20651" data-current-group="냉장배송 C"><input type="checkbox"><span class="product-thumb">○</span><span class="product-pick-copy"><strong>미니 치즈케이크 2입</strong><small>ND-20651 · 냉장 기본</small></span><span class="product-source">냉장배송 C</span></label>
            <label class="product-pick" data-product-name="대용량 보냉 컨테이너 48L" data-product-no="ND-20481" data-current-group="none"><input type="checkbox"><span class="product-thumb">▣</span><span class="product-pick-copy"><strong>대용량 보냉 컨테이너 48L</strong><small>ND-20481 · 대형배송</small></span><span class="product-source ungrouped">그룹 미지정</span></label>
            <label class="product-pick" data-product-name="프리미엄 유리 화병 세트" data-product-no="ND-20117" data-current-group="none"><input type="checkbox"><span class="product-thumb">●</span><span class="product-pick-copy"><strong>프리미엄 유리 화병 세트</strong><small>ND-20117 · 수량별 배송</small></span><span class="product-source ungrouped">그룹 미지정</span></label>
            <label class="product-pick" data-product-name="원목 사이드 테이블" data-product-no="ND-19802" data-current-group="none"><input type="checkbox"><span class="product-thumb">◇</span><span class="product-pick-copy"><strong>원목 사이드 테이블</strong><small>ND-19802 · 대형배송</small></span><span class="product-source ungrouped">그룹 미지정</span></label>
        </div>
        <div class="empty-picker" id="emptyProductPick">조건에 맞는 이동 가능 상품이 없습니다.</div>
    </div>
    <div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" id="moveProducts" type="button">선택 상품 이동</button></div>
</aside>

<div class="toast" id="toast" role="status"><span class="toast-check">✓</span><span id="toastText">저장되었습니다.</span></div>

<script>
    const overlay = document.getElementById('overlay');
    const conditionDrawer = document.getElementById('conditionDrawer');
    const applyDrawer = document.getElementById('applyDrawer');
    const groupDrawer = document.getElementById('groupDrawer');
    const productGroupDrawer = document.getElementById('productGroupDrawer');
    const toast = document.getElementById('toast');
    const toastText = document.getElementById('toastText');
    let toastTimer;
    let activeFee = 'conditional';
    let activeCalc = 'MAX';
    let editingRow = null;
    let targetGroupCard = null;
    let registerStep = 1;
    let selectedRegisterCondition = document.querySelector('#registerConditions .register-condition.selected');
    let applyTargetRows = [];
    let nextProductNumber = 21408;

    function showToast(message) {
        clearTimeout(toastTimer);
        toastText.textContent = message;
        toast.classList.add('show');
        toastTimer = setTimeout(() => toast.classList.remove('show'), 2400);
    }

    function openDrawer(drawer) {
        document.querySelectorAll('.drawer.open').forEach(item => item.classList.remove('open'));
        overlay.classList.add('open');
        drawer.classList.add('open');
        document.body.style.overflow = 'hidden';
        setTimeout(() => drawer.querySelector('input, select, button')?.focus(), 80);
    }

    function closeDrawers() {
        overlay.classList.remove('open');
        document.querySelectorAll('.drawer.open').forEach(item => item.classList.remove('open'));
        document.body.style.overflow = '';
        editingRow = null;
    }

    function updateUngroupedCount() {
        const count = document.querySelectorAll('#ungroupedProductList .product-row').length;
        document.getElementById('ungroupedCount').textContent = count;
        document.getElementById('emptyUngrouped').style.display = count ? 'none' : 'block';
        document.querySelector('.product-list-toolbar').style.display = count ? 'flex' : 'none';
    }

    function updateUngroupedSelection() {
        const rows = [...document.querySelectorAll('#ungroupedProductList .product-row')];
        const selectedRows = rows.filter(row => row.querySelector('.row-check').checked);
        rows.forEach(row => row.classList.toggle('selected', row.querySelector('.row-check').checked));
        const bulkButton = document.getElementById('openApplySecond');
        bulkButton.disabled = selectedRows.length === 0;
        bulkButton.textContent = `선택 상품 배송설정 (${selectedRows.length})`;
        const selectAll = document.getElementById('selectAllUngrouped');
        selectAll.checked = rows.length > 0 && selectedRows.length === rows.length;
        selectAll.indeterminate = selectedRows.length > 0 && selectedRows.length < rows.length;
    }

    function openProductShipping(rows) {
        applyTargetRows = rows.filter(row => row?.isConnected);
        if (!applyTargetRows.length) {
            showToast('배송설정을 변경할 상품을 선택해주세요.');
            return;
        }

        const first = applyTargetRows[0];
        const single = applyTargetRows.length === 1;
        document.getElementById('applyTitle').textContent = single ? '상품 배송설정' : '선택 상품 배송설정';
        document.getElementById('applyProductThumb').textContent = single ? first.dataset.thumb : '✓';
        document.getElementById('applyProductName').textContent = single ? first.dataset.productName : `선택한 상품 ${applyTargetRows.length}개`;
        document.getElementById('applyProductMeta').textContent = single ?
            `상품번호 ${first.dataset.productNo} · 현재 배송조건 ${first.dataset.condition}` :
            `${first.dataset.productName}${applyTargetRows.length > 1 ? ` 외 ${applyTargetRows.length - 1}개` : ''}에 같은 설정을 적용합니다.`;

        const conditionSelect = document.getElementById('applyCondition');
        const matchedOption = [...conditionSelect.options].find(option => option.value.split('|')[0] === first.dataset.condition);
        if (matchedOption) conditionSelect.value = matchedOption.value;
        document.getElementById('applyGroup').value = 'none';
        document.getElementById('applySave').textContent = single ? '상품에 적용' : `${applyTargetRows.length}개 상품에 적용`;
        updateApplyFlow();
        openDrawer(applyDrawer);
    }

    function bindUngroupedRow(row) {
        const checkbox = row.querySelector('.row-check');
        const settingButton = row.querySelector('.row-shipping-btn');
        checkbox.addEventListener('click', event => event.stopPropagation());
        checkbox.addEventListener('change', updateUngroupedSelection);
        settingButton.addEventListener('click', event => {
            event.stopPropagation();
            openProductShipping([row]);
        });
        row.addEventListener('click', event => {
            if (event.target.closest('button, input')) return;
            openProductShipping([row]);
        });
        row.addEventListener('keydown', event => {
            if (event.target !== row || !['Enter', ' '].includes(event.key)) return;
            event.preventDefault();
            openProductShipping([row]);
        });
    }

    document.querySelectorAll('#ungroupedProductList .product-row').forEach(bindUngroupedRow);
    document.getElementById('selectAllUngrouped').addEventListener('change', event => {
        document.querySelectorAll('#ungroupedProductList .row-check').forEach(checkbox => {
            checkbox.checked = event.target.checked;
        });
        updateUngroupedSelection();
    });
    updateUngroupedCount();
    updateUngroupedSelection();

    document.getElementById('openCreate').addEventListener('click', () => {
        resetConditionForm();
        openDrawer(conditionDrawer);
    });
    document.getElementById('openApply').addEventListener('click', () => openProductShipping([document.querySelector('#ungroupedProductList .product-row')]));
    document.getElementById('openApplySecond').addEventListener('click', () => {
        const selectedRows = [...document.querySelectorAll('#ungroupedProductList .product-row')]
            .filter(row => row.querySelector('.row-check').checked);
        openProductShipping(selectedRows);
    });
    document.getElementById('openGroupCreate').addEventListener('click', () => {
        document.getElementById('groupName').value = '';
        setCalculation('MAX');
        openDrawer(groupDrawer);
    });
    overlay.addEventListener('click', closeDrawers);
    document.querySelectorAll('[data-close]').forEach(button => button.addEventListener('click', closeDrawers));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeDrawers();
    });

    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.panel').forEach(item => item.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById(`panel-${tab.dataset.tab}`).classList.add('active');
            document.getElementById('openCreate').style.visibility = tab.dataset.tab === 'conditions' ? 'visible' : 'hidden';
        });
    });

    function updateRegisterShipping() {
        const groupSelect = document.getElementById('regGroup');
        const groupParts = groupSelect.value.split('|');
        const calcResult = document.getElementById('regCalcResult');
        const noGroup = groupParts[0] === 'none';
        calcResult.classList.toggle('grouped', !noGroup);
        calcResult.innerHTML = noGroup ?
            '<span class="info-dot">i</span><span><strong>개별배송으로 등록됩니다.</strong><br>배송그룹을 선택하지 않았으므로 이 상품의 배송비가 다른 상품과 별도로 합산됩니다.</span>' :
            `<span class="info-dot">i</span><span><strong>묶음배송으로 등록됩니다.</strong><br>‘${groupParts[1]}’ 상품과 함께 주문하면 ${groupParts[0]} 기준 배송비가 한 번 부과됩니다.</span>`;
    }

    function refreshRegisterConfirmation() {
        const name = document.getElementById('regProductName').value.trim() || '상품명 미입력';
        const category = document.getElementById('regCategory').value;
        const price = Number(document.getElementById('regPrice').value || 0);
        const stock = Number(document.getElementById('regStock').value || 0);
        const groupParts = document.getElementById('regGroup').value.split('|');
        const noGroup = groupParts[0] === 'none';
        document.getElementById('confirmName').textContent = name;
        document.getElementById('confirmCategory').textContent = category;
        document.getElementById('confirmPrice').textContent = formatWon(price);
        document.getElementById('confirmStock').textContent = `${stock.toLocaleString('ko-KR')}개`;
        document.getElementById('confirmCondition').textContent = selectedRegisterCondition.dataset.condition;
        document.getElementById('confirmFee').textContent = selectedRegisterCondition.dataset.fee;
        document.getElementById('confirmGroup').textContent = noGroup ? '개별배송 · 그룹 미지정' : `묶음배송 · ${groupParts[1]}`;
        document.getElementById('confirmCalc').textContent = groupParts[2];
        document.getElementById('confirmMessage').innerHTML = noGroup ?
            '<span class="info-dot">i</span><span>배송그룹을 선택하지 않아 개별배송으로 등록되며, 다른 상품과 주문 시 배송비가 합산됩니다.</span>' :
            `<span class="info-dot">i</span><span>‘${groupParts[1]}’ 상품과 묶음배송되며, ${groupParts[0]} 기준 배송비가 한 번 부과됩니다.</span>`;
        document.getElementById('completeProductName').textContent = name;
    }

    function setRegisterStep(step) {
        registerStep = step;
        document.querySelectorAll('[data-reg-screen]').forEach(screen => screen.classList.toggle('active', Number(screen.dataset.regScreen) === step));
        document.querySelectorAll('[data-reg-step]').forEach(item => {
            const itemStep = Number(item.dataset.regStep);
            item.classList.toggle('active', itemStep === step);
            item.classList.toggle('done', itemStep < step);
            item.querySelector('.step-circle').textContent = itemStep < step ? '✓' : itemStep;
        });
        const prev = document.getElementById('regPrev');
        const next = document.getElementById('regNext');
        const foot = document.getElementById('registerFoot');
        foot.style.display = step === 4 ? 'none' : 'flex';
        prev.style.visibility = step === 1 ? 'hidden' : 'visible';
        next.textContent = step === 1 ? '배송설정으로' : step === 2 ? '최종 확인' : '상품 등록';
        document.getElementById('regStepHint').textContent = step === 1 ?
            '필수 정보를 입력한 뒤 다음 단계로 이동하세요.' :
            step === 2 ?
            '배송조건은 필수이며, 배송그룹은 선택하지 않아도 됩니다.' :
            '내용을 확인한 뒤 상품을 등록하세요.';
        if (step === 3) refreshRegisterConfirmation();
    }

    function bindRegisterCondition(card) {
        if (card.dataset.bound) return;
        card.dataset.bound = 'true';
        card.addEventListener('click', () => {
            document.querySelectorAll('#registerConditions .register-condition').forEach(item => item.classList.remove('selected'));
            card.classList.add('selected');
            selectedRegisterCondition = card;
            updateRegisterShipping();
        });
    }

    document.querySelectorAll('#registerConditions .register-condition').forEach(bindRegisterCondition);

    function syncRegisterConditionCard(oldName, name, labels) {
        let card = [...document.querySelectorAll('#registerConditions .register-condition')]
            .find(item => item.dataset.condition === oldName);
        if (!card) {
            card = document.createElement('button');
            card.className = 'register-condition';
            card.type = 'button';
            document.getElementById('registerConditions').appendChild(card);
        }
        card.dataset.condition = name;
        card.dataset.fee = labels.main;
        card.dataset.rule = labels.sub || (labels.type === '무료' ? '배송비 무료' : '주문 1건당 부과');
        card.innerHTML = `<span class="radio-mark"></span><strong>${name.replace(/[<>&"']/g, '')}</strong><small>${labels.main}${labels.sub ? ` · ${labels.sub}` : ''}</small>`;
        bindRegisterCondition(card);
        if (card.classList.contains('selected')) selectedRegisterCondition = card;
    }

    function syncApplyConditionOption(oldName, name, labels) {
        let option = [...document.getElementById('applyCondition').options]
            .find(item => item.value.split('|')[0] === oldName);
        if (!option) {
            option = document.createElement('option');
            document.getElementById('applyCondition').appendChild(option);
        }
        option.value = `${name}|${labels.type} · ${labels.main}`;
        option.textContent = `${name} — ${labels.type} ${labels.main}`;
    }

    function adjustConditionUsage(conditionName, amount) {
        const row = [...document.querySelectorAll('#conditionRows tr')]
            .find(item => item.dataset.name === conditionName);
        const usage = row?.querySelector('.usage');
        if (!usage) return;
        const current = Number(usage.textContent.replace(/[^0-9]/g, '')) || 0;
        const next = Math.max(0, current + amount);
        usage.textContent = `${next}개`;
        usage.dataset.toast = `${conditionName} 조건을 사용하는 상품은 ${next}개입니다.`;
        countRows();
    }

    function createMoveCatalogItem({
        name,
        productNo,
        condition,
        groupName,
        thumb = '●'
    }) {
        const item = document.createElement('label');
        item.className = 'product-pick';
        item.dataset.productName = name;
        item.dataset.productNo = productNo;
        item.dataset.currentGroup = groupName;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        const thumbElement = document.createElement('span');
        thumbElement.className = 'product-thumb';
        thumbElement.textContent = thumb;
        const copy = document.createElement('span');
        copy.className = 'product-pick-copy';
        const strong = document.createElement('strong');
        strong.textContent = name;
        const small = document.createElement('small');
        small.textContent = `${productNo} · ${condition}`;
        copy.append(strong, small);
        const source = document.createElement('span');
        source.className = `product-source${groupName === 'none' ? ' ungrouped' : ''}`;
        source.textContent = groupName === 'none' ? '그룹 미지정' : groupName;
        item.append(checkbox, thumbElement, copy, source);
        document.getElementById('productPickList').appendChild(item);
    }

    function registrationFeeDetail(conditionName) {
        if (conditionName === '수량별 배송') return '수량별 반복 부과';
        if (conditionName === '금액 구간별 배송') return '상품 주문금액별 부과';
        if (conditionName === '무료배송') return '배송비 무료';
        return '개별 부과';
    }

    function createUngroupedProductRow({
        name,
        productNo,
        condition,
        fee,
        thumb = '●'
    }) {
        const safeName = name.replace(/[<>&"']/g, '');
        const safeCondition = condition.replace(/[<>&"']/g, '');
        const row = document.createElement('div');
        row.className = 'product-row';
        row.tabIndex = 0;
        row.dataset.productName = name;
        row.dataset.productNo = productNo;
        row.dataset.condition = condition;
        row.dataset.thumb = thumb;
        row.innerHTML = `
        <input class="row-check" type="checkbox" aria-label="${safeName} 선택">
        <div class="product-info"><div class="product-thumb">${thumb}</div><div><strong>${safeName}</strong><small>${productNo} · ${safeCondition} · 그룹 미지정</small></div></div>
        <div class="product-actions"><div class="product-fee"><strong>${fee}</strong><small>${registrationFeeDetail(condition)}</small></div><button class="btn btn-small row-shipping-btn" type="button">배송설정</button></div>`;
        document.getElementById('ungroupedProductList').appendChild(row);
        bindUngroupedRow(row);
        updateUngroupedCount();
        updateUngroupedSelection();
    }

    function registerProductIntoManagement() {
        const name = document.getElementById('regProductName').value.trim();
        const productNo = `ND-${nextProductNumber}`;
        nextProductNumber += 1;
        const condition = selectedRegisterCondition.dataset.condition;
        const fee = selectedRegisterCondition.dataset.fee;
        const groupParts = document.getElementById('regGroup').value.split('|');
        const groupName = groupParts[0] === 'none' ? 'none' : groupParts[1];

        createMoveCatalogItem({
            name,
            productNo,
            condition,
            groupName
        });
        adjustConditionUsage(condition, 1);
        if (groupName === 'none') createUngroupedProductRow({
            name,
            productNo,
            condition,
            fee
        });
        else adjustGroupProductCount(groupName, 1);

        document.getElementById('completeProductName').textContent = name;
        document.getElementById('completeProductNumber').textContent = `상품번호 ${productNo}`;
        document.getElementById('completeDestination').textContent = groupName === 'none' ?
            '그룹 미지정 상품 목록과 기존 상품 추가·이동 목록에 추가되었습니다.' :
            `‘${groupName}’ 상품 수와 기존 상품 추가·이동 목록에 반영되었습니다.`;
        return {
            name,
            productNo,
            groupName
        };
    }

    document.getElementById('regGroup').addEventListener('change', updateRegisterShipping);
    document.getElementById('mockUpload').addEventListener('click', () => showToast('프로토타입에서는 이미지 등록 화면만 확인할 수 있습니다.'));
    document.getElementById('regPrev').addEventListener('click', () => setRegisterStep(Math.max(1, registerStep - 1)));
    document.getElementById('regNext').addEventListener('click', () => {
        if (registerStep === 1) {
            const name = document.getElementById('regProductName');
            const price = document.getElementById('regPrice');
            if (!name.value.trim()) {
                name.focus();
                showToast('상품명을 입력해주세요.');
                return;
            }
            if (Number(price.value) <= 0) {
                price.focus();
                showToast('판매가를 입력해주세요.');
                return;
            }
        }
        if (registerStep < 3) setRegisterStep(registerStep + 1);
        else {
            const registered = registerProductIntoManagement();
            setRegisterStep(4);
            showToast(registered.groupName === 'none' ?
                `‘${registered.name}’ 상품이 그룹 미지정 목록에 추가되었습니다.` :
                `‘${registered.name}’ 상품이 ‘${registered.groupName}’에 추가되었습니다.`);
        }
    });

    document.getElementById('resetRegister').addEventListener('click', () => {
        document.getElementById('regProductName').value = '';
        document.getElementById('regPrice').value = '';
        document.getElementById('regStock').value = 0;
        document.getElementById('regGroup').selectedIndex = 0;
        document.querySelectorAll('#registerConditions .register-condition').forEach((item, index) => item.classList.toggle('selected', index === 0));
        selectedRegisterCondition = document.querySelector('#registerConditions .register-condition.selected');
        updateRegisterShipping();
        setRegisterStep(1);
        document.getElementById('regProductName').focus();
    });

    function setFeeType(fee) {
        activeFee = fee;
        document.getElementById('dc_type').value = fee;
        document.querySelectorAll('.fee-type').forEach(button => button.classList.toggle('active', button.dataset.fee === fee));
        const free = fee === 'free';
        const amountRange = fee === 'amount_range';
        document.getElementById('feeFields').style.display = free || amountRange ? 'none' : 'block';
        document.getElementById('amountRangeFields').style.display = amountRange ? 'block' : 'none';
        document.getElementById('thresholdWrap').style.display = fee === 'conditional' ? 'block' : 'none';
        document.getElementById('quantityWrap').style.display = fee === 'quantity' ? 'block' : 'none';
        updateFeePreview();
    }

    document.querySelectorAll('.fee-type').forEach(button => button.addEventListener('click', () => setFeeType(button.dataset.fee)));
    document.querySelectorAll('[data-switch]').forEach(button => button.addEventListener('click', () => button.classList.toggle('on')));
    ['baseFee', 'freeThreshold', 'repeatQuantity'].forEach(id => document.getElementById(id).addEventListener('input', updateFeePreview));

    function formatWon(value) {
        return `${Number(value || 0).toLocaleString('ko-KR')}원`;
    }

    function getAmountRanges() {
        return [...document.querySelectorAll('#amountRangeRows .amount-range-row')].map(row => {
            const maxValue = row.querySelector('.range-max').value;
            return {
                min: Number(row.querySelector('.range-min').value || 0),
                max: maxValue === '' ? null : Number(maxValue),
                fee: Number(row.querySelector('.range-fee').value || 0)
            };
        });
    }

    function rangeFeeText(fee) {
        return Number(fee) === 0 ? '무료' : formatWon(fee);
    }

    function amountRangePreview() {
        return getAmountRanges().map(range => range.max === null ?
            `${formatWon(range.min)} 이상: ${rangeFeeText(range.fee)}` :
            `${formatWon(range.min)} 이상 ~ ${formatWon(range.max)} 미만: ${rangeFeeText(range.fee)}`
        ).join('<br>');
    }

    // 배송조건 주문금액별 배송비 구간 구간 추가
    function createAmountRangeRow(min = '', max = '', fee = '') {
        const row = document.createElement('div');
        row.className = 'amount-range-row';
        row.innerHTML = `
        <label class="range-input"><input class="range-min" name="dc_range_min[]" type="number" min="0" step="1000" value="${min ?? ''}" aria-label="시작 금액"><span>원</span></label>
        <label class="range-input"><input class="range-max" name="dc_range_max[]" type="number" min="0" step="1000" value="${max ?? ''}" placeholder="제한 없음" aria-label="종료 금액"><span>원</span></label>
        <label class="range-input"><input class="range-fee" name="dc_range_price[]" type="number" min="0" step="100" value="${fee ?? ''}" aria-label="배송비"><span>원</span></label>
        <button class="range-remove" type="button" aria-label="구간 삭제">×</button>`;
        bindAmountRangeRow(row);
        return row;
    }

    function bindAmountRangeRow(row) {
        if (row.dataset.bound) return;
        row.dataset.bound = 'true';
        row.querySelectorAll('input').forEach(input => input.addEventListener('input', updateFeePreview));
        row.querySelector('.range-remove').addEventListener('click', () => {
            const rows = document.querySelectorAll('#amountRangeRows .amount-range-row');
            if (rows.length === 1) {
                showToast('배송비 구간은 최소 1개가 필요합니다.');
                return;
            }
            row.remove();
            updateFeePreview();
        });
    }

    function resetAmountRanges(ranges = [{
            min: 0,
            max: 10000,
            fee: 3000
        },
        {
            min: 10000,
            max: 20000,
            fee: 4000
        },
        {
            min: 20000,
            max: null,
            fee: 0
        }
    ]) {
        const holder = document.getElementById('amountRangeRows');
        holder.innerHTML = '';
        ranges.forEach(range => holder.appendChild(createAmountRangeRow(range.min, range.max, range.fee)));
    }

    function validateAmountRanges() {
        const ranges = getAmountRanges();
        if (!ranges.length) return '배송비 구간을 1개 이상 입력해주세요.';
        if (ranges[0].min !== 0) return '첫 구간은 0원 이상부터 시작해주세요.';
        for (let index = 0; index < ranges.length; index += 1) {
            const range = ranges[index];
            if (range.max !== null && range.max <= range.min) return `${index + 1}번째 구간의 종료 금액은 시작 금액보다 커야 합니다.`;
            if (range.max === null && index !== ranges.length - 1) return '종료 금액이 없는 구간은 마지막에만 둘 수 있습니다.';
            if (index > 0 && ranges[index - 1].max !== range.min) return `${index}번째 종료 금액과 ${index + 1}번째 시작 금액을 같게 입력해주세요.`;
        }
        if (ranges[ranges.length - 1].max !== null) return '마지막 구간의 종료 금액을 비워 상한 없음으로 설정해주세요.';
        return '';
    }

    document.querySelectorAll('#amountRangeRows .amount-range-row').forEach(bindAmountRangeRow);
    document.getElementById('addAmountRange').addEventListener('click', () => {
        const rows = [...document.querySelectorAll('#amountRangeRows .amount-range-row')];
        const last = rows[rows.length - 1];
        const lastMin = Number(last.querySelector('.range-min').value || 0);
        const lastMaxInput = last.querySelector('.range-max');
        if (lastMaxInput.value === '') lastMaxInput.value = lastMin + 10000;
        const start = Number(lastMaxInput.value || 0);
        document.getElementById('amountRangeRows').appendChild(createAmountRangeRow(start, '', 0));
        updateFeePreview();
    });

    function updateFeePreview() {
        const fee = formatWon(document.getElementById('baseFee').value);
        const threshold = formatWon(document.getElementById('freeThreshold').value);
        const quantity = document.getElementById('repeatQuantity').value || 1;
        let copy = `주문 1건에 배송비 ${fee}이 적용됩니다.`;
        if (activeFee === 'conditional') copy = `주문금액 ${threshold} 미만이면 ${fee}, 이상이면 무료로 적용됩니다.`;
        if (activeFee === 'free') copy = '주문금액과 관계없이 배송비가 무료로 적용됩니다.';
        if (activeFee === 'quantity') copy = `상품 ${quantity}개마다 배송비 ${fee}이 반복 부과됩니다.`;
        if (activeFee === 'amount_range') copy = `상품 주문금액을 기준으로 아래 배송비가 적용됩니다.<br>${amountRangePreview()}`;
        document.getElementById('feePreview').innerHTML = `<strong>미리보기</strong><br>${copy}<br><span style="color:var(--muted)">묶음·개별 여부는 상품의 배송그룹 선택에서 결정됩니다.</span>`;
    }

    function resetConditionForm() {
        editingRow = null;
        document.getElementById('drawerTitle').textContent = '배송조건 추가';
        document.getElementById('conditionName').value = '';
        document.getElementById('baseFee').value = 3000;
        document.getElementById('freeThreshold').value = 50000;
        document.getElementById('repeatQuantity').value = 1;
        resetAmountRanges();
        setFeeType('conditional');
    }

    function countRows() {
        const rows = [...document.querySelectorAll('#conditionRows tr')];
        const appliedProducts = rows.reduce((sum, row) => {
            const usage = row.querySelector('.usage')?.textContent || '0';
            return sum + (Number(usage.replace(/[^0-9]/g, '')) || 0);
        }, 0);
        document.getElementById('summaryCount').textContent = rows.length;
        document.getElementById('tabConditionCount').textContent = rows.length;
        document.getElementById('footerCount').textContent = rows.length;
        document.getElementById('appliedProductCount').textContent = appliedProducts;
    }

    function feeLabels() {
        const base = formatWon(document.getElementById('baseFee').value);
        if (activeFee === 'free') return {
            type: '무료',
            main: '0원',
            sub: ''
        };
        if (activeFee === 'paid') return {
            type: '유료',
            main: base,
            sub: '주문 1건당 부과'
        };
        if (activeFee === 'quantity') return {
            type: '수량별',
            main: base,
            sub: `${document.getElementById('repeatQuantity').value || 1}개마다 반복`
        };
        if (activeFee === 'amount_range') {
            const ranges = getAmountRanges();
            const last = ranges[ranges.length - 1];
            const sub = last.max === null ? `${formatWon(last.min)} 이상 ${rangeFeeText(last.fee)}` : `${formatWon(last.min)}부터 ${rangeFeeText(last.fee)}`;
            return {
                type: '금액 구간별',
                main: `구간 ${ranges.length}개`,
                sub
            };
        }
        return {
            type: '조건부 무료',
            main: base,
            sub: `${formatWon(document.getElementById('freeThreshold').value)} 이상 무료`
        };
    }

    function bindRowActions(scope = document) {
        scope.querySelectorAll('.usage, [data-toast]').forEach(button => {
            if (button.dataset.bound) return;
            button.dataset.bound = 'true';
            button.addEventListener('click', () => showToast(button.dataset.toast || '해당 기능으로 이동합니다.'));
        });
        scope.querySelectorAll('.clone-condition').forEach(button => {
            if (button.dataset.bound) return;
            button.dataset.bound = 'true';
            button.addEventListener('click', () => {
                const row = button.closest('tr');
                resetConditionForm();
                document.getElementById('conditionName').value = `${row.dataset.name} 복사본`;
                if (row.dataset.type === 'amount_range' && row.dataset.ranges) resetAmountRanges(JSON.parse(row.dataset.ranges));
                setFeeType(row.dataset.type);
                openDrawer(conditionDrawer);
            });
        });
        scope.querySelectorAll('.edit-condition').forEach(button => {
            if (button.dataset.bound) return;
            button.dataset.bound = 'true';
            button.addEventListener('click', () => {
                editingRow = button.closest('tr');
                document.getElementById('drawerTitle').textContent = '배송조건 수정';
                document.getElementById('conditionName').value = editingRow.dataset.name;
                if (editingRow.dataset.type === 'amount_range' && editingRow.dataset.ranges) resetAmountRanges(JSON.parse(editingRow.dataset.ranges));
                setFeeType(editingRow.dataset.type);
                openDrawer(conditionDrawer);
            });
        });
    }

    // 배송 조건 추가 Form 전송 로직
    $("#fdeliverycondition").on("submit", function(e) {
        e.preventDefault();

        const nameInput = document.getElementById('conditionName');
        const name = nameInput.value.trim();
        if (!name) {
            nameInput.focus();
            nameInput.style.borderColor = 'var(--red)';
            showToast('배송조건명을 입력해주세요.');
            return;
        }
        nameInput.style.borderColor = '';
        if (activeFee === 'amount_range') {
            const rangeError = validateAmountRanges();
            if (rangeError) {
                showToast(rangeError);
                return;
            }
        }
        const labels = feeLabels();
        if (editingRow) {
            const oldName = editingRow.dataset.name;
            editingRow.dataset.name = name;
            editingRow.dataset.type = activeFee;
            if (activeFee === 'amount_range') editingRow.dataset.ranges = JSON.stringify(getAmountRanges());
            else delete editingRow.dataset.ranges;
            editingRow.querySelector('.condition-name strong').textContent = name;
            editingRow.children[1].textContent = labels.type;
            editingRow.children[2].innerHTML = `<strong>${labels.main}</strong>${labels.sub ? `<span class="condition-desc">${labels.sub}</span>` : ''}`;
            syncRegisterConditionCard(oldName, name, labels);
            syncApplyConditionOption(oldName, name, labels);
            updateRegisterShipping();
            updateApplyFlow();
            closeDrawers();
            showToast(`‘${name}’ 조건이 수정되었습니다.`);
        } else {
            const row = document.createElement('tr');
            row.dataset.type = activeFee;
            row.dataset.name = name;
            if (activeFee === 'amount_range') row.dataset.ranges = JSON.stringify(getAmountRanges());
            row.innerHTML = `
          <td><div class="condition-name"><strong>${name.replace(/[<>&"']/g, '')}</strong></div><span class="condition-desc">직접 추가한 배송조건</span></td>
          <td>${labels.type}</td>
          <td><strong>${labels.main}</strong>${labels.sub ? `<span class="condition-desc">${labels.sub}</span>` : ''}</td>
          <td><button class="usage" type="button" data-toast="아직 이 조건을 사용하는 상품이 없습니다.">0개</button></td>
          <td><span class="badge badge-bundle">사용 중</span></td>
          <td><div class="row-actions"><button class="text-btn edit-condition" type="button">수정</button><button class="text-btn clone-condition" type="button">복제</button></div></td>`;
            document.getElementById('conditionRows').appendChild(row);
            syncRegisterConditionCard(null, name, labels);
            syncApplyConditionOption(null, name, labels);
            bindRowActions(row);
            countRows();
            filterRows();
            closeDrawers();
            showToast(`‘${name}’ 배송조건이 추가되었습니다.`);
        }
    });

    function filterRows() {
        const query = document.getElementById('conditionSearch').value.trim().toLowerCase();
        const type = document.getElementById('typeFilter').value;
        let visible = 0;
        document.querySelectorAll('#conditionRows tr').forEach(row => {
            const matchesName = row.dataset.name.toLowerCase().includes(query);
            const matchesType = type === 'all' || row.dataset.type === type;
            const show = matchesName && matchesType;
            row.style.display = show ? '' : 'none';
            if (show) visible += 1;
        });
        document.getElementById('emptySearch').style.display = visible ? 'none' : 'block';
    }

    document.getElementById('conditionSearch').addEventListener('input', filterRows);
    document.getElementById('typeFilter').addEventListener('change', filterRows);

    function updateApplyFlow() {
        const selected = document.getElementById('applyCondition').value.split('|');
        const group = document.getElementById('applyGroup');
        const branch = document.getElementById('branchBox');
        const noGroup = group.value === 'none';
        branch.classList.toggle('individual', noGroup);
        branch.innerHTML = noGroup ?
            '<span class="info-dot">i</span><span><strong>개별배송으로 등록됩니다.</strong><br>배송그룹을 선택하지 않아 상품별 배송비가 합산됩니다.</span>' :
            `<span class="info-dot">i</span><span><strong>묶음배송으로 등록됩니다.</strong><br>‘${group.value}’ 상품과 함께 배송비가 한 번 계산됩니다.</span>`;
        document.getElementById('summaryCondition').textContent = selected[0];
        document.getElementById('summaryFee').textContent = selected[1];
        document.getElementById('summaryGroup').textContent = noGroup ? '개별배송 · 그룹 미지정' : `묶음배송 · ${group.value}`;
    }

    document.getElementById('applyCondition').addEventListener('change', updateApplyFlow);
    document.getElementById('applyGroup').addEventListener('change', updateApplyFlow);
    document.getElementById('applySave').addEventListener('click', () => {
        const targets = applyTargetRows.filter(row => row.isConnected);
        if (!targets.length) {
            showToast('적용할 상품을 다시 선택해주세요.');
            return;
        }

        const selected = document.getElementById('applyCondition').value.split('|');
        const groupName = document.getElementById('applyGroup').value;
        const feeMain = selected[1].split(' · ').pop();
        const feeDetail = selected[0] === '수량별 배송' ?
            '수량별 반복 부과' :
            selected[0] === '금액 구간별 배송' ?
            '상품 주문금액별 부과' :
            selected[0] === '무료배송' ?
            '배송비 무료' :
            '개별 부과';

        targets.forEach(row => {
            const previousCondition = row.dataset.condition;
            row.dataset.condition = selected[0];
            row.querySelector('.product-info small').textContent = `${row.dataset.productNo} · ${selected[0]} · 그룹 미지정`;
            row.querySelector('.product-fee strong').textContent = feeMain;
            row.querySelector('.product-fee small').textContent = feeDetail;
            row.querySelector('.row-check').checked = false;
            updateProductCatalogCondition(row.dataset.productNo, selected[0]);
            if (previousCondition !== selected[0]) {
                adjustConditionUsage(previousCondition, -1);
                adjustConditionUsage(selected[0], 1);
            }
        });

        if (groupName !== 'none') {
            adjustGroupProductCount(groupName, targets.length);
            targets.forEach(row => {
                updateProductCatalogGroup(row.dataset.productNo, groupName);
                row.remove();
            });
        }

        updateUngroupedCount();
        updateUngroupedSelection();
        closeDrawers();
        showToast(groupName === 'none' ?
            `${targets.length}개 상품의 배송조건을 변경했습니다.` :
            `${targets.length}개 상품을 ‘${groupName}’ 묶음배송으로 이동했습니다.`);
    });

    function setCalculation(calculation) {
        activeCalc = calculation;
        document.querySelectorAll('#calcChoices .choice-card').forEach(card => {
            const selected = card.dataset.calc === calculation;
            card.classList.toggle('selected', selected);
            card.querySelector('input').checked = selected;
        });
        document.getElementById('calcExplain').innerHTML = calculation === 'MAX' ?
            '<strong>MAX 예시</strong> · 같은 그룹에서 배송비 3,000원과 4,000원 상품을 함께 구매하면 4,000원을 한 번 부과합니다.' :
            '<strong>MIN 예시</strong> · 같은 그룹에서 배송비 3,000원과 4,000원 상품을 함께 구매하면 3,000원을 한 번 부과합니다.';
    }

    document.querySelectorAll('#calcChoices .choice-card').forEach(card => {
        card.addEventListener('click', () => setCalculation(card.dataset.calc));
    });

    function adjustGroupProductCount(groupName, amount) {
        if (!groupName || groupName === 'none') return;
        const card = [...document.querySelectorAll('#groupGrid .group-card')]
            .find(item => item.dataset.groupName === groupName);
        const countElement = card?.querySelector('.group-product-count');
        if (!countElement) return;
        const current = Number(countElement.textContent.replace(/[^0-9]/g, '')) || 0;
        countElement.textContent = `${Math.max(0, current + amount)}개`;
    }

    function updateProductCatalogGroup(productNo, groupName) {
        const item = [...document.querySelectorAll('#productPickList .product-pick')]
            .find(product => product.dataset.productNo === productNo);
        if (!item) return;
        item.dataset.currentGroup = groupName;
        const source = item.querySelector('.product-source');
        source.textContent = groupName === 'none' ? '그룹 미지정' : groupName;
        source.classList.toggle('ungrouped', groupName === 'none');
    }

    function updateProductCatalogCondition(productNo, conditionName) {
        const item = [...document.querySelectorAll('#productPickList .product-pick')]
            .find(product => product.dataset.productNo === productNo);
        if (!item) return;
        item.querySelector('.product-pick-copy small').textContent = `${productNo} · ${conditionName}`;
    }

    function rebuildProductSourceFilter(targetGroupName) {
        const filter = document.getElementById('productSourceFilter');
        filter.innerHTML = '<option value="all">다른 그룹 + 미지정 전체</option><option value="none">그룹 미지정</option>';
        document.querySelectorAll('#groupGrid .group-card').forEach(card => {
            if (card.dataset.groupName === targetGroupName) return;
            const option = document.createElement('option');
            option.value = card.dataset.groupName;
            option.textContent = card.dataset.groupName;
            filter.appendChild(option);
        });
        filter.value = 'all';
    }

    function refreshProductMoveList() {
        const targetGroupName = targetGroupCard?.dataset.groupName || '';
        const query = document.getElementById('productPickSearch').value.trim().toLowerCase();
        const sourceFilter = document.getElementById('productSourceFilter').value;
        let visible = 0;
        document.querySelectorAll('#productPickList .product-pick').forEach(item => {
            const currentGroup = item.dataset.currentGroup;
            const eligible = currentGroup !== targetGroupName;
            const matchesSource = sourceFilter === 'all' || currentGroup === sourceFilter;
            const searchable = `${item.dataset.productName} ${item.dataset.productNo}`.toLowerCase();
            const show = eligible && matchesSource && searchable.includes(query);
            item.style.display = show ? 'flex' : 'none';
            if (!eligible) item.querySelector('input').checked = false;
            if (show) visible += 1;
        });
        document.getElementById('productPickCount').textContent = visible;
        document.getElementById('emptyProductPick').style.display = visible ? 'none' : 'block';
        document.getElementById('productPickTarget').textContent = targetGroupName ? `‘${targetGroupName}’ 기존 상품 제외` : '대상 그룹 기존 상품 제외';
    }

    function bindGroupProductButtons(scope = document) {
        scope.querySelectorAll('.group-products').forEach(button => {
            if (button.dataset.bound) return;
            button.dataset.bound = 'true';
            button.addEventListener('click', () => {
                targetGroupCard = button.closest('.group-card');
                const groupName = targetGroupCard.dataset.groupName;
                document.getElementById('productGroupTitle').textContent = `‘${groupName}’으로 상품 이동`;
                document.getElementById('productGroupSub').textContent = '다른 그룹 상품과 그룹 미지정 상품만 선택할 수 있습니다.';
                document.querySelectorAll('#productPickList input').forEach(input => {
                    input.checked = false;
                });
                document.getElementById('productPickSearch').value = '';
                rebuildProductSourceFilter(groupName);
                refreshProductMoveList();
                openDrawer(productGroupDrawer);
            });
        });
    }

    document.getElementById('saveGroup').addEventListener('click', () => {
        const input = document.getElementById('groupName');
        const name = input.value.trim();
        if (!name) {
            input.focus();
            input.style.borderColor = 'var(--red)';
            showToast('그룹명을 입력해주세요.');
            return;
        }
        input.style.borderColor = '';
        const safeName = name.replace(/[<>&"']/g, '');
        const card = document.createElement('article');
        card.className = 'group-card';
        card.dataset.groupName = safeName;
        card.innerHTML = `<div class="group-top"><div><h3>${safeName}</h3><p>새로 생성한 묶음배송 그룹</p></div><span class="badge badge-bundle">사용 중</span></div><div class="group-meta"><div><span>상품 수</span><strong class="group-product-count">0개</strong></div><div><span>계산 방식</span><strong>${activeCalc}</strong></div><div><span>적용 조건</span><strong>0개</strong></div></div><button class="btn btn-small group-products" type="button" style="width:100%; margin-top:14px">기존 상품 추가·이동</button>`;
        document.getElementById('groupGrid').appendChild(card);
        const option = document.createElement('option');
        option.value = safeName;
        option.textContent = `${safeName} · ${activeCalc}`;
        document.getElementById('applyGroup').appendChild(option);
        const registerOption = document.createElement('option');
        registerOption.value = `${activeCalc}|${safeName}|그룹 내 ${activeCalc === 'MAX' ? '최고' : '최저'} 배송비 1회`;
        registerOption.textContent = `${safeName} · ${activeCalc}`;
        document.getElementById('regGroup').appendChild(registerOption);
        document.getElementById('tabGroupCount').textContent = document.querySelectorAll('#groupGrid .group-card').length;
        bindGroupProductButtons(card);
        closeDrawers();
        showToast(`‘${safeName}’ 그룹이 ${activeCalc} 방식으로 생성되었습니다.`);
    });

    document.getElementById('productPickSearch').addEventListener('input', refreshProductMoveList);
    document.getElementById('productSourceFilter').addEventListener('change', refreshProductMoveList);

    document.getElementById('moveProducts').addEventListener('click', () => {
        const selectedItems = [...document.querySelectorAll('#productPickList .product-pick')]
            .filter(item => item.querySelector('input').checked && item.dataset.currentGroup !== targetGroupCard?.dataset.groupName);
        if (!selectedItems.length) {
            showToast('이동할 상품을 한 개 이상 선택해주세요.');
            return;
        }
        const groupName = targetGroupCard?.dataset.groupName || '선택한 그룹';
        selectedItems.forEach(item => {
            const previousGroup = item.dataset.currentGroup;
            if (previousGroup === 'none') {
                const ungroupedRow = [...document.querySelectorAll('#ungroupedProductList .product-row')]
                    .find(row => row.dataset.productNo === item.dataset.productNo);
                ungroupedRow?.remove();
            } else {
                adjustGroupProductCount(previousGroup, -1);
            }
            updateProductCatalogGroup(item.dataset.productNo, groupName);
            item.querySelector('input').checked = false;
        });
        adjustGroupProductCount(groupName, selectedItems.length);
        updateUngroupedCount();
        updateUngroupedSelection();
        closeDrawers();
        showToast(`${selectedItems.length}개 상품을 ‘${groupName}’으로 이동했습니다.`);
    });

    bindRowActions();
    bindGroupProductButtons();
    updateFeePreview();
    updateApplyFlow();
    updateRegisterShipping();
    setRegisterStep(1);
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
