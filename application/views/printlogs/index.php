<?php
// Small helpers for this page
$e = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };

$today     = date('Y-m-d');
$base_url  = site_url('printlogs');
$presets   = array(
    'Today'       => array($today, $today),
    'Yesterday'   => array(date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('-1 day'))),
    'Last 7 days' => array(date('Y-m-d', strtotime('-6 days')), $today),
    'This month'  => array(date('Y-m-01'), $today),
);

$total      = $summary['total'];
$early      = $summary['early'];
$early_rate = $total > 0 ? round(($early / $total) * 100) : 0;
$from_row   = $total > 0 ? $offset + 1 : 0;
$to_row     = min($offset + $per_page, $total);

if (!function_exists('pl_type_class')) {
    function pl_type_class($t)
    {
        if (strpos($t, 'invoice') === 0) { return 'pl-t-inv'; }
        if (strpos($t, 'do_') === 0)     { return 'pl-t-do'; }
        if ($t === 'packing_list')       { return 'pl-t-pack'; }
        return 'pl-t-prod';
    }
}
?>
<style>
.pl-page { --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --paper:#ffffff; --bg:#f6f7f9;
           --red:#b42318; --red-bg:#fef3f2; --green:#067647; --green-bg:#ecfdf3;
           --amber:#b54708; --amber-bg:#fffaeb; --blue:#175cd3; --blue-bg:#eff8ff;
           color:var(--ink); padding:18px 20px 40px; }
.pl-page * { box-sizing:border-box; }

.pl-top { display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:12px; margin-bottom:18px; }
.pl-top h2 { margin:0; font-size:24px; font-weight:600; }
.pl-top p  { margin:4px 0 0; color:var(--muted); font-size:14px; }

.pl-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 15px; border-radius:8px; border:1px solid var(--line);
          background:var(--paper); color:var(--ink); font-size:14px; font-weight:500; cursor:pointer; text-decoration:none; line-height:1.2; }
.pl-btn:hover { background:#f3f4f6; color:var(--ink); text-decoration:none; }
.pl-btn:focus-visible, .pl-field input:focus-visible, .pl-field select:focus-visible { outline:2px solid var(--blue); outline-offset:2px; }
.pl-btn.primary { background:#1f2937; border-color:#1f2937; color:#fff; }
.pl-btn.primary:hover { background:#111827; color:#fff; }

/* summary */
.pl-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:14px; margin-bottom:18px; }
.pl-stat  { background:var(--paper); border:1px solid var(--line); border-radius:10px; padding:15px 18px; }
.pl-stat .n { font-size:30px; font-weight:600; line-height:1.1; font-variant-numeric:tabular-nums; }
.pl-stat .l { margin-top:5px; color:var(--muted); font-size:13px; }
.pl-stat.early { border-color:#fecdca; background:var(--red-bg); }
.pl-stat.early .n { color:var(--red); }

/* filters */
.pl-panel { background:var(--paper); border:1px solid var(--line); border-radius:10px; padding:16px 18px; margin-bottom:18px; }
.pl-presets { display:flex; flex-wrap:wrap; gap:8px; align-items:center; margin-bottom:14px; font-size:13px; color:var(--muted); }
.pl-chip { padding:5px 12px; border-radius:999px; border:1px solid var(--line); color:var(--ink); text-decoration:none; font-size:13px; background:#fff; }
.pl-chip:hover { background:#f3f4f6; color:var(--ink); text-decoration:none; }
.pl-filters { display:grid; grid-template-columns:repeat(auto-fit,minmax(165px,1fr)); gap:12px; align-items:end; }
.pl-field label { display:block; margin:0 0 5px; font-size:13px; font-weight:500; color:var(--muted); }
.pl-field input[type=date], .pl-field select { width:100%; height:38px; padding:0 10px; border:1px solid #d1d5db; border-radius:8px; background:#fff; font-size:14px; color:var(--ink); }
.pl-check { display:flex; align-items:center; gap:8px; height:38px; font-size:14px; cursor:pointer; }
.pl-check input { width:17px; height:17px; margin:0; }
.pl-actions { display:flex; flex-wrap:wrap; gap:10px; margin-top:16px; padding-top:14px; border-top:1px solid var(--line); }
.pl-actions .spacer { flex:1; }

/* table */
.pl-tablebox { background:var(--paper); border:1px solid var(--line); border-radius:10px; overflow:hidden; }
.pl-scroll { overflow-x:auto; }
.pl-table { width:100%; border-collapse:collapse; font-size:14px; min-width:780px; }
.pl-table th { text-align:left; font-weight:600; font-size:13px; color:var(--muted); background:#f9fafb; padding:11px 16px; border-bottom:1px solid var(--line); white-space:nowrap; }
.pl-table td { padding:12px 16px; border-bottom:1px solid #f0f1f3; vertical-align:middle; }
.pl-table tr:last-child td { border-bottom:0; }
.pl-table tbody tr:hover td { background:#fafafa; }
.pl-table tr.is-early td:first-child { box-shadow:inset 4px 0 0 var(--red); }
.pl-table tr.is-early td { background:#fffbfa; }
.pl-table .num { color:var(--muted); font-variant-numeric:tabular-nums; }
.pl-table .sub { display:block; color:var(--muted); font-size:12.5px; margin-top:2px; }
.pl-table .when { font-variant-numeric:tabular-nums; white-space:nowrap; }

.pl-badge { display:inline-block; padding:3px 10px; border-radius:6px; font-size:13px; font-weight:500; white-space:nowrap; }
.pl-t-prod { background:#fff4ed; color:#9a3412; }
.pl-t-pack { background:#eff8ff; color:#175cd3; }
.pl-t-inv  { background:#f4f3ff; color:#5925dc; }
.pl-t-do   { background:#ecfdff; color:#0e7090; }
.pl-s-early { background:var(--red-bg);   color:var(--red); }
.pl-s-ok    { background:var(--green-bg); color:var(--green); }
.pl-s-same  { background:var(--amber-bg); color:var(--amber); }
.pl-s-late  { background:#f2f4f7; color:#475467; }

.pl-user { display:flex; align-items:center; gap:10px; }
.pl-avatar { width:30px; height:30px; border-radius:50%; background:#e0e7ff; color:#3730a3; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:600; flex:none; }

.pl-empty { padding:46px 20px; text-align:center; color:var(--muted); }
.pl-empty strong { display:block; color:var(--ink); font-size:16px; margin-bottom:4px; }

.pl-foot { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; padding:13px 16px; border-top:1px solid var(--line); background:#f9fafb; font-size:13px; color:var(--muted); }
.pl-pager { display:flex; flex-wrap:wrap; gap:4px; list-style:none; margin:0; padding:0; }
.pl-pager li a, .pl-pager li span { display:block; min-width:34px; padding:6px 10px; text-align:center; border:1px solid var(--line); border-radius:7px; background:#fff; color:var(--ink); text-decoration:none; font-size:13px; }
.pl-pager li a:hover { background:#f3f4f6; }
.pl-pager li.active span { background:#1f2937; border-color:#1f2937; color:#fff; }

@media (max-width:600px) { .pl-page { padding:14px 10px 30px; } .pl-top h2 { font-size:21px; } }
</style>

<div class="content-wrapper">
  <section class="content">
    <div class="pl-page">

      <div class="pl-top">
        <div>
          <h2>Print logs</h2>
          <p>Every time a production list, packing list or invoice was printed, with who printed it and when.</p>
        </div>
        <a href="<?php echo $e(site_url('Dashboardcontroller')); ?>" class="pl-btn">Back to dashboard</a>
      </div>

      <!-- Summary for the current filters -->
      <div class="pl-stats">
        <div class="pl-stat">
          <div class="n"><?php echo number_format($total); ?></div>
          <div class="l">Prints in this view</div>
        </div>
        <div class="pl-stat early">
          <div class="n"><?php echo number_format($early); ?></div>
          <div class="l">Printed more than 1 day before delivery</div>
        </div>
        <div class="pl-stat">
          <div class="n"><?php echo $early_rate; ?>%</div>
          <div class="l">Share of prints that were early</div>
        </div>
        <div class="pl-stat">
          <div class="n"><?php echo number_format($summary['users']); ?></div>
          <div class="l">People who printed</div>
        </div>
      </div>

      <!-- Filters -->
      <form method="get" action="<?php echo $e($base_url); ?>" class="pl-panel">
        <div class="pl-presets">
          <span>Printed:</span>
          <?php foreach ($presets as $label => $range): ?>
            <a class="pl-chip" href="<?php echo $e($base_url . '?printed_from=' . $range[0] . '&printed_to=' . $range[1]); ?>"><?php echo $e($label); ?></a>
          <?php endforeach; ?>
          <a class="pl-chip" href="<?php echo $e($base_url . '?printed_from=&printed_to='); ?>">All time</a>
        </div>

        <div class="pl-filters">
          <div class="pl-field">
            <label for="printed_from">Printed from</label>
            <input type="date" id="printed_from" name="printed_from" value="<?php echo $e($filters['printed_from']); ?>">
          </div>
          <div class="pl-field">
            <label for="printed_to">Printed to</label>
            <input type="date" id="printed_to" name="printed_to" value="<?php echo $e($filters['printed_to']); ?>">
          </div>
          <div class="pl-field">
            <label for="delivery_from">Delivery date from</label>
            <input type="date" id="delivery_from" name="delivery_from" value="<?php echo $e($filters['delivery_from']); ?>">
          </div>
          <div class="pl-field">
            <label for="delivery_to">Delivery date to</label>
            <input type="date" id="delivery_to" name="delivery_to" value="<?php echo $e($filters['delivery_to']); ?>">
          </div>
          <div class="pl-field">
            <label for="doc_type">Document</label>
            <select id="doc_type" name="doc_type">
              <option value="">All documents</option>
              <?php foreach ($doc_types as $key => $label): ?>
                <option value="<?php echo $e($key); ?>" <?php echo $filters['doc_type'] === $key ? 'selected' : ''; ?>><?php echo $e($label); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="pl-field">
            <label for="user_id">Printed by</label>
            <select id="user_id" name="user_id">
              <option value="">Everyone</option>
              <?php foreach ($users_list as $u): ?>
                <option value="<?php echo $e($u->id); ?>" <?php echo (string) $filters['user_id'] === (string) $u->id ? 'selected' : ''; ?>>
                  <?php echo $e($u->name ? $u->name : 'User'); ?> (ID <?php echo $e($u->id); ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="pl-field">
            <label class="pl-check" style="margin:0;">
              <input type="checkbox" name="early" value="1" <?php echo $filters['early'] === '1' ? 'checked' : ''; ?>>
              Early prints only
            </label>
          </div>
        </div>

        <div class="pl-actions">
          <button type="submit" class="pl-btn primary">Apply filters</button>
          <a href="<?php echo $e($base_url); ?>" class="pl-btn">Reset</a>
          <span class="spacer"></span>
          <a href="<?php echo $e($export_url); ?>" class="pl-btn">Download CSV</a>
        </div>
      </form>

      <!-- Results -->
      <div class="pl-tablebox">
        <div class="pl-scroll">
          <table class="pl-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Document</th>
                <th>Delivery date</th>
                <th>Printed on</th>
                <th>Timing</th>
                <th>Printed by</th>
                <th>IP address</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="7">
                <div class="pl-empty">
                  <strong>No prints match these filters</strong>
                  Widen the date range or choose "All time" to see more.
                </div>
              </td></tr>
            <?php else: foreach ($rows as $r):
                $d      = (int) $r->days_before;
                $label  = isset($doc_types[$r->document_type]) ? $doc_types[$r->document_type] : $r->document_type;
                $name   = $r->printed_by_name ? $r->printed_by_name : 'Unknown user';
                $init   = strtoupper(substr(trim($name), 0, 1));

                if ($d > 1)       { $s_cls = 'pl-s-early'; $s_txt = 'Early, ' . $d . ' days before'; }
                elseif ($d === 1) { $s_cls = 'pl-s-ok';    $s_txt = 'Day before'; }
                elseif ($d === 0) { $s_cls = 'pl-s-same';  $s_txt = 'Same day'; }
                else              { $s_cls = 'pl-s-late';  $s_txt = abs($d) . ($d === -1 ? ' day' : ' days') . ' after'; }

                $ts = strtotime($r->printed_at);
            ?>
              <tr class="<?php echo $d > 1 ? 'is-early' : ''; ?>">
                <td class="num"><?php echo $e($r->id); ?></td>
                <td><span class="pl-badge <?php echo pl_type_class($r->document_type); ?>"><?php echo $e($label); ?></span></td>
                <td class="when">
                  <?php echo $e(date('d M Y', strtotime($r->delivery_date))); ?>
                  <span class="sub"><?php echo $e(date('l', strtotime($r->delivery_date))); ?></span>
                </td>
                <td class="when">
                  <?php echo $e(date('d M Y', $ts)); ?>
                  <span class="sub"><?php echo $e(date('h:i:s A', $ts)); ?></span>
                </td>
                <td><span class="pl-badge <?php echo $s_cls; ?>"><?php echo $e($s_txt); ?></span></td>
                <td>
                  <div class="pl-user">
                    <span class="pl-avatar"><?php echo $e($init); ?></span>
                    <span><?php echo $e($name); ?><span class="sub">User ID <?php echo $e($r->printed_by); ?></span></span>
                  </div>
                </td>
                <td class="num"><?php echo $e($r->ip_address); ?></td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>

        <div class="pl-foot">
          <span>
            <?php if ($total > 0): ?>
              Showing <?php echo number_format($from_row); ?> to <?php echo number_format($to_row); ?> of <?php echo number_format($total); ?> prints
            <?php else: ?>
              No results
            <?php endif; ?>
          </span>
          <?php echo $pagination; ?>
        </div>
      </div>

    </div>
  </section>
</div>
