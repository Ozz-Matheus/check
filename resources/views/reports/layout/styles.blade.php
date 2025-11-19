<style>
    @charset "UTF-8";

    /* ============================================
   EXECUTIVE REPORTS SHARED STYLES
   Para reportes de Riesgos y Auditorías
   ============================================ */

    /* PAGE SETUP */
    @page {
        margin: 4cm 1.5cm 2.5cm 1.5cm;
    }

    /* BODY AND CONTAINER */
    body {
        font-family: 'Segoe UI', 'Helvetica Neue', 'Arial', sans-serif;
        font-size: 10px;
        color: #2c3e50;
        line-height: 1.5;
    }

    .container {
        width: 100%;
        margin: 0 auto;
    }

    /* HEADER & FOOTER */
    header {
        position: fixed;
        top: -3cm;
        left: 0cm;
        right: 0cm;
        height: 2.5cm;
        text-align: center;
        border-bottom: 2px solid #3498db;
    }

    footer {
        position: fixed;
        bottom: -2cm;
        left: 0cm;
        right: 0cm;
        height: 1.5cm;
        text-align: center;
        font-size: 9px;
        color: #7f8c8d;
        border-top: 1px solid #bdc3c7;
        padding-top: 10px;
    }

    footer .page-number:before {
        content: "Página " counter(page);
    }

    .logo {
        width: 150px;
        height: auto;
        border: 2px dashed #3498db;
        padding: 15px;
        text-align: center;
        color: #3498db;
        font-size: 12px;
        font-weight: bold;
        margin: 0 auto 10px;
        background-color: #ecf0f1;
    }

    /* TYPOGRAPHY */
    h1 {
        color: #2c3e50;
        font-size: 24px;
        text-align: center;
        margin-bottom: 25px;
        border-bottom: 4px solid #3498db;
        padding-bottom: 15px;
        text-transform: uppercase;
        font-weight: bold;
    }

    h2 {
        color: #2c3e50;
        font-size: 16px;
        font-weight: bold;
        margin-top: 30px;
        margin-bottom: 15px;
        border-left: 5px solid #3498db;
        padding-left: 15px;
        page-break-after: avoid;
        background-color: #ecf0f1;
        padding: 10px 10px 10px 15px;
    }

    h3 {
        color: #34495e;
        font-size: 13px;
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 12px;
        page-break-after: avoid;
        border-bottom: 2px solid #bdc3c7;
        padding-bottom: 5px;
    }

    h4 {
        color: #2c3e50;
        font-size: 11px;
        font-weight: bold;
        margin: 10px 0 5px 0;
    }

    /* TABLES */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    th,
    td {
        border: 1px solid #bdc3c7;
        padding: 8px;
        text-align: left;
        vertical-align: middle;
    }

    th {
        background-color: #3498db;
        color: white;
        font-weight: bold;
        text-align: center;
        font-size: 9px;
    }

    tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    /* INFO TABLE */
    .info-table td {
        border: 1px solid #dfe6e9;
        padding: 8px 12px;
    }

    .info-table td:first-child {
        background-color: #ecf0f1;
        font-weight: bold;
        color: #2c3e50;
        width: 30%;
    }

    /* STATISTICS GRID */
    .stats-grid {
        display: table;
        width: 100%;
        margin-bottom: 25px;
    }

    .stat-box {
        display: table-cell;
        width: 16.66%;
        text-align: center;
        padding: 15px;
        border: 2px solid #3498db;
        background: linear-gradient(to bottom, #ffffff, #ecf0f1);
    }

    .stat-number {
        font-size: 28px;
        font-weight: bold;
        color: #2c3e50;
        display: block;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 9px;
        color: #7f8c8d;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* STATUS COLORS - Generic levels */
    .status-success,
    .level-success {
        background-color: #d4edda !important;
        color: #155724 !important;
    }

    .status-yellow,
    .level-yellow {
        background-color: #fff3cd !important;
        color: #856404 !important;
    }

    .status-warning,
    .level-warning {
        background-color: #ffe5d0 !important;
        color: #d63301 !important;
    }

    .status-danger,
    .level-danger {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        font-weight: bold;
        font-size: 9px;
    }

    .badge-yellow {
        background-color: #ffc107;
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        font-weight: bold;
        font-size: 9px;
    }

    .badge-warning {
        background-color: #fd7e14;
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        font-weight: bold;
        font-size: 9px;
    }

    .badge-danger {
        background-color: #dc3545;
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        font-weight: bold;
        font-size: 9px;
    }

    /* HEATMAP */
    .heatmap {
        border: 2px solid #34495e;
        margin-bottom: 15px;
    }

    .heatmap th,
    .heatmap td {
        width: 16%;
        height: 45px;
        text-align: center;
        vertical-align: middle;
        font-size: 11px;
    }

    .heatmap .axis-label {
        width: 12%;
        font-weight: bold;
        background-color: #34495e !important;
        color: white;
        font-size: 10px;
    }

    .heatmap td {
        font-size: 18px;
        font-weight: bold;
    }

    .heatmap-title {
        background-color: #2c3e50;
        color: white;
        padding: 8px;
        text-align: center;
        font-weight: bold;
        font-size: 11px;
    }

    .heatmap-legend {
        display: table;
        width: 100%;
        margin-bottom: 25px;
        border: 1px solid #bdc3c7;
        padding: 10px;
        background-color: #f8f9fa;
    }

    .legend-item {
        display: inline-block;
        margin-right: 20px;
        padding: 5px 12px;
        border-radius: 3px;
        font-size: 9px;
        font-weight: bold;
    }

    /* BLOCK STYLES */
    .risk-block,
    .audit-block,
    .finding-block-container {
        border: 2px solid #bdc3c7;
        padding: 15px;
        margin-bottom: 20px;
        page-break-inside: avoid;
        background-color: #ffffff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .block-header,
    .risk-header,
    .audit-header {
        background-color: #34495e;
        color: white;
        padding: 10px;
        margin: -15px -15px 15px -15px;
        border-radius: 3px 3px 0 0;
        font-weight: bold;
        font-size: 11px;
    }

    .code-badge,
    .risk-code,
    .audit-code {
        background-color: #3498db;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: bold;
        margin-right: 10px;
    }

    /* DETAIL TABLES */
    .detail-table {
        margin-bottom: 12px;
        font-size: 9px;
    }

    .detail-table th {
        background-color: #34495e;
        padding: 6px;
    }

    .detail-table td {
        padding: 6px;
    }

    /* SECTION BOXES */
    .section-box {
        background-color: #f8f9fa;
        border-left: 4px solid #3498db;
        padding: 12px;
        margin: 15px 0;
    }

    .section-title {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 10px;
    }

    /* ITEM STYLES */
    .control-item {
        background-color: #e8f4f8;
        border-left: 3px solid #3498db;
        padding: 8px;
        margin: 8px 0;
        font-size: 9px;
    }

    .cause-item {
        background-color: #fff3e0;
        border-left: 3px solid #ff9800;
        padding: 8px;
        margin: 8px 0;
        font-size: 9px;
    }

    .finding-item {
        background-color: #fffde7;
        border-left: 4px solid #f9a825;
        padding: 10px;
        margin: 8px 0;
        font-size: 9px;
    }

    .action-item {
        background-color: #e8f5e9;
        border-left: 3px solid #4caf50;
        padding: 8px;
        margin: 8px 0;
        font-size: 9px;
    }

    /* LISTS */
    ul {
        padding-left: 20px;
        margin: 10px 0;
    }

    li {
        margin-bottom: 8px;
        line-height: 1.6;
    }

    ol {
        padding-left: 20px;
        margin: 10px 0;
    }

    /* UTILITIES */
    .page-break {
        page-break-after: always;
    }

    .text-center {
        text-align: center;
    }

    .text-bold {
        font-weight: bold;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .mt-20 {
        margin-top: 20px;
    }

    /* CHART BOX */
    .chart-box {
        border: 1px solid #bdc3c7;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #ffffff;
    }

    .chart-title {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 11px;
        border-bottom: 2px solid #3498db;
        padding-bottom: 5px;
    }

    /* COMPARISON TABLE */
    .comparison-table {
        width: 100%;
        margin-bottom: 25px;
    }

    .comparison-table th {
        background-color: #34495e;
    }

    /* TOTALS TABLE */
    .totals-table td {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        padding: 15px;
    }

    /* ALERT BOXES */
    .alert-info {
        background-color: #d1ecf1;
        border-left: 4px solid #17a2b8;
        padding: 12px;
        margin: 15px 0;
        color: #0c5460;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 12px;
        margin: 15px 0;
        color: #856404;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-left: 4px solid #dc3545;
        padding: 12px;
        margin: 15px 0;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        border-left: 4px solid #28a745;
        padding: 12px;
        margin: 15px 0;
        color: #155724;
    }

    /* PROGRESS BAR */
    .progress-bar-container {
        width: 100%;
        height: 20px;
        background-color: #ecf0f1;
        border-radius: 10px;
        overflow: hidden;
        margin: 10px 0;
    }

    .progress-bar {
        height: 100%;
        background-color: #3498db;
        text-align: center;
        line-height: 20px;
        color: white;
        font-size: 9px;
        font-weight: bold;
    }

    /* CONFIDENTIAL FOOTER */
    .confidential-footer {
        margin-top: 30px;
        padding: 15px;
        background-color: #ecf0f1;
        border: 2px solid #3498db;
        text-align: center;
    }

    .confidential-footer p {
        margin: 0;
        font-weight: bold;
        color: #2c3e50;
    }

    /* TWO COLUMN LAYOUT */
    .two-column {
        width: 48%;
        display: inline-block;
        vertical-align: top;
    }

    .two-column-right {
        margin-left: 3%;
    }

    /* RESPONSIVE ADJUSTMENTS FOR PRINT */
    @media print {
        body {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .no-print {
            display: none;
        }
    }
</style>
