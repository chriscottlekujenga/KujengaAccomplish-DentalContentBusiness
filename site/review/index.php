<?php
/**
 * Brush With Me — Clinical Review Form (Password-Gated) — v3
 * URL: brushwithme.com/review  (this file = index.php inside /review/)
 * Gate: session-based password check (hash below)
 * Features: per-item links to the referenced content (GitHub), draft autosave
 *           via localStorage (restores across visits, clears on submit),
 *           submissions posted to the GitHub repo as an Issue (primary),
 *           + timestamped backup in /review/reviews/ (always, independent of email)
 * Token:    fine-grained PAT (Issues: read/write) read from /home4/ab39928/.gh_review_token
 *           (one level above the webroot; never inside it, never committed)
 */

define('PASSWORD_HASH', '$2y$10$Zjb2g1qYEhk2YIypCKkxZeHb49HwH6FW2N.ebaysdrQD3Vic4P9dW'); // bcrypt ($2y$ for PHP compat), generated 2026-09-26
define('OWNER_EMAIL', 'chris@webkujenga.com'); // demoted to best-effort notification; not load-bearing since v3
define('BRAND', 'Brush With Me');
$BACKUP_DIR = __DIR__ . '/reviews';

// GitHub submission target
define('GH_API', 'https://api.github.com/repos/chriscottlekujenga/KujengaAccomplish-DentalContentBusiness/issues');
define('GH_REPO_URL', 'https://github.com/chriscottlekujenga/KujengaAccomplish-DentalContentBusiness/issues');
define('GH_TOKEN_PATH', '/home4/ab39928/.gh_review_token'); // outside webroot; owner uploads via cPanel

// GitHub base for content links
define('GH', 'https://github.com/chriscottlekujenga/KujengaAccomplish-DentalContentBusiness/blob/main/');

session_start();

// ===== GATE =====
$unlocked = isset($_SESSION['review_unlocked']) && $_SESSION['review_unlocked'] === true;
if (!$unlocked && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gate_password'])) {
  if (password_verify($_POST['gate_password'], PASSWORD_HASH)) {
    $_SESSION['review_unlocked'] = true;
    $unlocked = true;
  } else {
    $gate_error = 'That password doesn\'t look right — try again.';
  }
}
if ($unlocked && isset($_GET['lock'])) { session_destroy(); $unlocked = false; }

// ===== CONTENT LINKS (per item) =====
// id => [label, GitHub path]
$LINKS = [
  'A1' => ['Brand brief (byline rules)', 'docs/BRAND_BRIEF.md'],
  'A2' => ['Example of the voice (post 01)', 'content/posts/01-brush-or-floss-first.md'],
  'A3' => ['Affiliate shortlist (brands)', 'content/AFFILIATE_SHORTLIST.md'],
  'A4' => ['Brand brief (never-say list)', 'docs/BRAND_BRIEF.md'],
  '9a' => ['Post 09 — Sugar & Kids Teeth', 'content/posts/09-sugar-and-kids-teeth.md'],
  '9b' => ['Post 09 — Sugar & Kids Teeth', 'content/posts/09-sugar-and-kids-teeth.md'],
  '9c' => ['Post 09 — Sugar & Kids Teeth', 'content/posts/09-sugar-and-kids-teeth.md'],
  '10a' => ['Post 10 — Bad Breath Basics', 'content/posts/10-bad-breath-basics.md'],
  '10b' => ['Post 10 — Bad Breath Basics', 'content/posts/10-bad-breath-basics.md'],
  '11a' => ['Post 11 — Dentist Visit Prep', 'content/posts/11-preparing-child-for-dentist.md'],
  '11b' => ['Post 11 — Dentist Visit Prep', 'content/posts/11-preparing-child-for-dentist.md'],
  '12a' => ['Post 12 — Electric Brush Guide', 'content/posts/12-electric-toothbrush-buying-guide.md'],
  '12b' => ['Post 12 — Electric Brush Guide', 'content/posts/12-electric-toothbrush-buying-guide.md'],
  '12c' => ['Post 12 — Electric Brush Guide', 'content/posts/12-electric-toothbrush-buying-guide.md'],
  '13a' => ['Post 13 — First Dental Visit', 'content/posts/13-first-dental-visit-baby.md'],
  '13b' => ['Post 13 — First Dental Visit', 'content/posts/13-first-dental-visit-baby.md'],
  '14a' => ['Post 14 — Morning vs Night', 'content/posts/14-morning-vs-night-brushing.md'],
  '14b' => ['Post 14 — Morning vs Night', 'content/posts/14-morning-vs-night-brushing.md'],
  '15a' => ['Post 15 — Whitening Basics', 'content/posts/15-teeth-whitening-basics.md'],
  '15b' => ['Post 15 — Whitening Basics', 'content/posts/15-teeth-whitening-basics.md'],
  '15c' => ['Post 15 — Whitening Basics', 'content/posts/15-teeth-whitening-basics.md'],
  '15d' => ['Post 15 — Whitening Basics', 'content/posts/15-teeth-whitening-basics.md'],
  'P1' => ['Brushing Battles Kit spec', 'content/products/PRODUCT_01_brushing_battles_kit.md'],
  'P2' => ['K-2 Classroom Pack spec', 'content/products/PRODUCT_02_classroom_pack.md'],
  'P3' => ['Grades 3-5 Pack spec', 'content/products/PRODUCT_02A_classroom_pack_3-5.md'],
  'P4' => ['Homeschool Unit spec', 'content/products/PRODUCT_02B_homeschool_pack.md'],
  'P5' => ['Routine Charts spec', 'content/products/PRODUCT_03_routine_charts.md'],
  'P6' => ['21-Day Challenge spec', 'content/products/LEAD_MAGNET_PRODUCTION.md'],
  'P7' => ['All product specs (pricing)', 'content/products/'],
  'E1' => ['Email sequence (all 4)', 'content/MAILERLITE_SETUP.md'],
  'E2' => ['Email sequence (all 4)', 'content/MAILERLITE_SETUP.md'],
  'E3' => ['Email sequence (all 4)', 'content/MAILERLITE_SETUP.md'],
  'E4' => ['Email sequence (all 4)', 'content/MAILERLITE_SETUP.md'],
  'E5' => ['Email sequence (all 4)', 'content/MAILERLITE_SETUP.md'],
  'V1' => ['Video scripts (all 4)', 'content/video/VIDEO_SCRIPTS_BATCH_01.md'],
  'V2' => ['Video scripts (all 4)', 'content/video/VIDEO_SCRIPTS_BATCH_01.md'],
  'V3' => ['Video scripts (all 4)', 'content/video/VIDEO_SCRIPTS_BATCH_01.md'],
  'V4' => ['Video scripts (all 4)', 'content/video/VIDEO_SCRIPTS_BATCH_01.md'],
  'V5' => ['Video scripts (all 4)', 'content/video/VIDEO_SCRIPTS_BATCH_01.md'],
  'F1' => ['Affiliate shortlist', 'content/AFFILIATE_SHORTLIST.md'],
  'F2' => ['Brand brief', 'docs/BRAND_BRIEF.md'],
  'F3' => ['Etsy shop About copy', 'content/ETSY_SHOP_SKELETON.md'],
];

// ===== ITEMS (mirrors PACKET_02) =====
$SECTIONS = [
  'A. Systematic Decisions (answer once — these become permanent writing rules)' => [
    ['A1', 'Byline: "Reviewed by Jessie [Last Name], RDH — [X] years of clinical dental hygiene experience." How many years? Last name OK, or first-name-only?'],
    ['A2', 'Tone: chairside-first voice ("I saw this for years...") — comfortable as the bylined expert?'],
    ['A3', 'Affiliate categories — any you would REFUSE to recommend? (current: electric brushes, floss picks, water flossers, toothpaste, tongue scrapers, mouthwash, whitening, dentist play kits, books, snack boxes)'],
    ['A4', 'Never-say list — any additions? (current: "prevents cavities" on products, "cure bad breath," "replaces your dentist," specific-condition diagnosis)'],
  ],
  'B. Post Claims — Sugar & Kids Teeth' => [
    ['9a', '"20-30 min acid attack per exposure; frequency matters more than total amount"'],
    ['9b', '"Dried fruit behaves more like candy than fruit" (sticky-cling framing)'],
    ['9c', 'Xylitol gum (age 4+) "may slow bacterial growth" — comfortable with this phrasing?'],
  ],
  'B. Post Claims — Bad Breath Basics' => [
    ['10a', '"Most ongoing bad breath originates in the mouth — back of the tongue is the #1 spot"'],
    ['10b', '"Persistent breath changes surviving proper hygiene → book the appointment" framing (gum issues, dry mouth, systemic mention — worded OK?)'],
  ],
  'B. Post Claims — Dentist Visit Prep' => [
    ['11a', 'The 5 rules — especially "never use the dentist as a threat" and "don\'t promise no pain/no needles" — align with your chairside guidance?'],
    ['11b', '"First visit by first birthday" — consistent with your guidance?'],
  ],
  'B. Post Claims — Electric Brush Buying Guide (Adults)' => [
    ['12a', '"A $25-50 brush with timer + pressure sensor cleans as well as a $200 one"'],
    ['12b', '"Oscillating-rotating has the deeper evidence trail; sonic wins for sensitive gag reflexes"'],
    ['12c', '"UV sanitizers and whitening modes are marketing" — too absolute?'],
  ],
  'B. Post Claims — First Dental Visit (Baby)' => [
    ['13a', 'Knee-to-knee exam walkthrough (greeting → exam → clean/fluoride → questions) — accurate?'],
    ['13b', 'Cost section: $50-200 no-insurance range; "upsell at a first visit = red flag" — fair and accurate?'],
  ],
  'B. Post Claims — Morning vs Night Brushing' => [
    ['14a', '"Saliva drops sharply overnight; the night brush is the non-negotiable one"'],
    ['14b', '"Morning brush\'s job is fresh breath + day prep — a different job than the night brush"'],
  ],
  'B. Post Claims — Whitening Basics' => [
    ['15a', '"Peroxide is the only effective whitening category" (surface-stain abrasives aside)'],
    ['15b', 'Charcoal + acid-rinse damage framing — accurate, and OK on tone?'],
    ['15c', '"Natural shade ceiling ≈ the whites of your eyes" rule of thumb'],
    ['15d', '"Whitening doesn\'t work on crowns/veneers/fillings — mismatch risk"'],
  ],
  'C. Product Specs (the money claims)' => [
    ['P1', 'Brushing Battles Kit ($24): parent scripts + troubleshooter — behavior-focused, never diagnosis? "Hygienist-reviewed" phrasing OK?'],
    ['P2', 'K-2 Classroom Pack ($12): teacher talking points (fluoride question, "why do we spit") — age-appropriate and accurate?'],
    ['P3', 'Grades 3-5 Pack ($12): eggshell experiment method + all 16 myth-vs-fact answers correct?'],
    ['P4', 'Homeschool Unit ($14): Smile Interview + read-aloud wording; co-op license terms reasonable?'],
    ['P5', 'Routine Charts ($9): chart steps for ages 2-8 appropriate? Anything clinical that shouldn\'t be?'],
    ['P6', '21-Day Challenge (free magnet): "how it works" page + 2-minute rule box accurate? Certificate wording OK?'],
    ['P7', 'Pricing overall: $24/$12/$14/$9 vs category norms — any positioning concern?'],
  ],
  'D. Email Sequence (written from your voice)' => [
    ['E1', 'Delivery email: tone + "aim for consecutive" reset advice OK?'],
    ['E2', '"3 mistakes" email: all 3 claims match your clinical view? (45-second average, bedtime-highest-risk, ambush-fear)'],
    ['E3', 'Kit offer email: "if this doesn\'t help, email me" guarantee — comfortable?'],
    ['E4', 'Long-game email: 2-2-2 recap + "never miss two in a row" rule OK?'],
    ['E5', 'All emails: sign-off "Jessie, RDH" + "hit reply, I read everything" — OK?'],
  ],
  'E. Video Scripts (4 faceless videos)' => [
    ['V1', 'Brush/floss order — matches the claims you reviewed?'],
    ['V2', '2-2-2 rule — matches?'],
    ['V3', 'Toothpaste amounts (rice-grain/pea/ribbon + fluoride-from-first-tooth) — matches?'],
    ['V4', 'Electric brush for kids — matches?'],
    ['V5', 'All videos: "educational, not dental advice" disclosure sufficient?'],
  ],
  'F. Approval Gates' => [
    ['F1', 'Affiliate brands — MySmile, Quip, Amazon, BURST, GLO Science: each approved for use on the site?'],
    ['F2', '"Brush With Me" brand + tagline "reviewed by a Registered Dental Hygienist" — approved with your credential?'],
    ['F3', 'Etsy About page: "years of chairside experience" + first-name use — confirm the number and wording'],
    ['F4', 'Anything else you want changed before anything publishes?'],
  ],
];

// ===== HANDLE REVIEW SUBMIT =====
$sent = false; $error = '';
if ($unlocked && $_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['gate_password'])) {
  $lines = [strtoupper(BRAND) . ' — CLINICAL REVIEW SUBMITTED ' . date('Y-m-d H:i')];
  $lines[] = str_repeat('-', 60);
  foreach ($SECTIONS as $section => $items) {
    $lines[] = '';
    $lines[] = '== ' . $section . ' ==';
    foreach ($items as [$id, $label]) {
      $verdict = $_POST['v_' . $id] ?? '(no selection)';
      $comment = trim($_POST['c_' . $id] ?? '');
      $lines[] = "$id [$verdict] " . ($comment !== '' ? "— \"$comment\"" : '');
    }
  }
  $overall = trim($_POST['overall'] ?? '');
  if ($overall !== '') { $lines[] = ''; $lines[] = '== OVERALL NOTES =='; $lines[] = $overall; }
  $body = implode("\n", $lines);

  // ---- Channel 1 (primary): GitHub Issue ----
  $gh_issue_url = ''; $gh_http = 0; $missing = [];
  $token = '';
  if (is_readable(GH_TOKEN_PATH)) { $token = trim((string)file_get_contents(GH_TOKEN_PATH)); }
  if ($token === '') { $missing[] = 'token file missing at ' . GH_TOKEN_PATH; }
  else {
    $payload = json_encode([
      'title'  => '[' . BRAND . '] Clinical review submitted ' . date('Y-m-d'),
      'body'   => $body,
      'labels' => ['clinical-review'],
    ]);
    $ch = curl_init(GH_API);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => $payload,
      CURLOPT_TIMEOUT        => 15,
      CURLOPT_CONNECTTIMEOUT => 8,
      CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $token,
        'Accept: application/vnd.github+json',
        'X-GitHub-Api-Version: 2022-11-28',
        'Content-Type: application/json',
        'User-Agent: brushwithme-review-form',
      ],
    ]);
    $resp = curl_exec($ch);
    $gh_http = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    if ($gh_http === 201 && $resp) {
      $data = json_decode($resp, true);
      $gh_issue_url = $data['html_url'] ?? '';
    } elseif ($gh_http === 401 || $gh_http === 403 || $gh_http === 404) {
      $missing[] = 'GitHub rejected the token (HTTP ' . $gh_http . ') — check PAT scope/expiry';
    } elseif ($gh_http === 0) {
      $missing[] = 'could not reach api.github.com (network/DNS)';
    } else {
      $missing[] = 'GitHub API HTTP ' . $gh_http;
    }
  }

  // ---- Channel 2: server backup file (always attempted, independent) ----
  if (!is_dir($BACKUP_DIR)) { @mkdir($BACKUP_DIR, 0755); }
  $ok_backup = @file_put_contents($BACKUP_DIR . '/review_' . date('Y-m-d_His') . '.txt', $body);

  // ---- Channel 3 (best-effort notification only): email ----
  $host = $_SERVER['HTTP_HOST'] ?? 'brushwithme.com';
  @$mail_ok = @mail(OWNER_EMAIL, '[' . BRAND . '] Clinical review submitted ' . date('Y-m-d'), $body,
      "From: no-reply@$host\r\nContent-Type: text/plain; charset=UTF-8");
  $ok1 = ($gh_issue_url !== '');

  $sent = $ok1 || (bool)$ok_backup; // success if EITHER GitHub issue or backup file landed
  if ($sent && $ok_backup && !$ok1 && !empty($missing)) {
    $error = 'Saved on the server, but the GitHub copy failed (' . implode('; ', $missing) . '). Tell Chris — nothing is lost.';
  } elseif (!$sent) {
    $error = 'Could not save or send — tell Chris; your answers are still on screen (do not refresh).';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?php echo BRAND; ?> — Clinical Review</title>
<style>
  :root { --teal:#1d6f64; --mint:#b8ded4; --cream:#fdf8f2; }
  * { box-sizing: border-box; }
  body { font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif; background: var(--cream); color: #222; margin: 0; padding: 16px; line-height: 1.45; }
  .wrap { max-width: 860px; margin: 0 auto; }
  h1 { color: var(--teal); font-size: 1.5rem; margin: 8px 0 4px; }
  .sub { color: #666; font-size: .95rem; margin-bottom: 16px; }
  .notice { background: #eafaf6; border-left: 4px solid var(--teal); padding: 12px 14px; border-radius: 6px; margin-bottom: 20px; font-size: .95rem; }
  .restore { background: #fff8e1; border: 1px solid #e8d27c; color: #7a6515; padding: 10px 14px; border-radius: 6px; margin: 0 0 14px; font-size: .9rem; display: none; }
  h2 { color: var(--teal); font-size: 1.05rem; margin: 28px 0 10px; border-bottom: 2px solid var(--mint); padding-bottom: 6px; }
  .item { background: #fff; border: 1px solid #d8e6e2; border-radius: 8px; padding: 12px 14px; margin: 10px 0; }
  .item .label { display: flex; gap: 10px; }
  .item .id { font-weight: 700; color: var(--teal); min-width: 42px; }
  .item .text { flex: 1; }
  .content-link { display: inline-block; margin-top: 6px; font-size: .85rem; color: var(--teal); text-decoration: none; border-bottom: 1px dotted var(--teal); }
  .content-link:hover { border-bottom-style: solid; }
  .choices { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 10px; font-size: .95rem; }
  .choices label { display: flex; align-items: center; gap: 6px; cursor: pointer; }
  textarea { width: 100%; margin-top: 10px; border: 1px solid #c7d8d3; border-radius: 6px; padding: 8px; font: inherit; font-size: .92rem; min-height: 60px; resize: vertical; }
  textarea::placeholder { color: #999; }
  .overall-area { width: 100%; min-height: 110px; margin-top: 8px; }
  button { display: block; width: 100%; background: var(--teal); color: #fff; font-size: 1.1rem; font-weight: 700; border: 0; border-radius: 10px; padding: 16px; margin: 26px 0 10px; cursor: pointer; }
  button:hover { background: #175a51; }
  .success { background: #e8f7ee; border: 1px solid #7fbf9a; color: #1b6b3a; padding: 18px; border-radius: 10px; text-align: center; font-size: 1.05rem; margin-top: 20px; }
  .err { background: #fdecea; border: 1px solid #e5a49c; color: #8a2b1d; padding: 12px; border-radius: 8px; margin-top: 10px; }
  .small { color: #777; font-size: .85rem; }
  .saved-note { font-size: .8rem; color: #2a7d4f; margin-top: 8px; opacity: 0; transition: opacity .4s; }
  .saved-note.show { opacity: 1; }
  /* gate */
  .gate-card { background: #fff; border: 1px solid #d8e6e2; border-radius: 12px; padding: 28px; max-width: 420px; margin: 60px auto 0; text-align: center; }
  .gate-card input[type=password] { width: 100%; padding: 12px; font-size: 1rem; border: 1px solid #c7d8d3; border-radius: 8px; margin: 16px 0 8px; }
  .lock-ico { font-size: 2rem; }
  @media (max-width: 640px) { .item .label { flex-direction: column; gap: 4px; } .item .id { min-width: 0; } }
</style>
</head>
<body>
<div class="wrap">

<?php if (!$unlocked): ?>
  <!-- ============ PASSWORD GATE ============ -->
  <div class="gate-card">
    <div class="lock-ico">🔒</div>
    <h1 style="margin-top:8px;"><?php echo BRAND; ?></h1>
    <p class="sub" style="margin-bottom:0;">Clinical Review — password required</p>
    <?php if (!empty($gate_error)): ?><div class="err"><?php echo htmlspecialchars($gate_error); ?></div><?php endif; ?>
    <form method="post" action="">
      <input type="password" name="gate_password" placeholder="Password" autofocus required>
      <button type="submit">Enter →</button>
    </form>
    <p class="small">Jessie — the password is in the message Chris sent you.</p>
  </div>

<?php elseif ($sent): ?>
  <!-- ============ SUCCESS ============ -->
  <div class="success">
    <b>Thank you, Jessie — your review is in. 🎉</b><br>
    <?php if ($ok1 && $gh_issue_url !== ''): ?>
      Posted to the project tracker: <a href="<?php echo htmlspecialchars($gh_issue_url); ?>" target="_blank" rel="noopener">view it here ↗</a><br>
    <?php endif; ?>
    <?php if ($ok_backup): ?><span class="small">Backup copy also saved on the server.</span><br><?php endif; ?>
    Fixes get applied to everything, and your rules get recorded for all future content.<br><br>
    <span class="small">Nothing publishes without your sign-off on that item.</span>
  </div>
  <script>try { localStorage.removeItem('bwm_review_draft_v1'); } catch(e) {}</script>

<?php else: ?>
  <!-- ============ REVIEW FORM ============ -->
  <h1>🦷 <?php echo BRAND; ?> — Clinical Review</h1>
  <div class="sub">Sprint output, Days 1–10 · 37 items · est. 90–120 min (future weekly packets run 60–90)</div>
  <?php if (!empty($error)): ?><div class="err"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
  <div class="notice">
    <b>How this works:</b> each row = one claim or decision. Mark <b>Approve</b>, <b>Fix</b>, or <b>Skip</b> — add a comment on any Fix (or wherever you have thoughts). Each item has a
    <b>View content ↗</b> link to the exact document it refers to. Your progress <b>saves automatically</b> as you go — leave and come back anytime on this device; it clears after you submit.
    Submit at the bottom — it lands straight in the project tracker. Works on your phone. <a href="?lock=1">Lock again</a>
  </div>
  <div class="restore" id="restoreNote">✏️ <b>Restored your saved progress</b> — continue where you left off.</div>

  <form method="post" action="" id="reviewForm">
  <?php foreach ($SECTIONS as $section => $items): ?>
    <h2><?php echo htmlspecialchars($section); ?></h2>
    <?php foreach ($items as [$id, $label]): ?>
      <div class="item">
        <div class="label"><span class="id"><?php echo $id; ?></span><span class="text"><?php echo htmlspecialchars($label); ?></span></div>
        <?php if (isset($LINKS[$id])): ?>
          <a class="content-link" href="<?php echo htmlspecialchars(GH . $LINKS[$id][1]); ?>" target="_blank" rel="noopener">📄 View content ↗ <span class="small"><?php echo htmlspecialchars($LINKS[$id][0]); ?></span></a>
        <?php endif; ?>
        <div class="choices">
          <label><input type="radio" name="v_<?php echo $id; ?>" value="Approve" required> ✓ Approve</label>
          <label><input type="radio" name="v_<?php echo $id; ?>" value="Fix"> ✏️ Fix</label>
          <label><input type="radio" name="v_<?php echo $id; ?>" value="Skip"> — Skip / N/A</label>
        </div>
        <textarea name="c_<?php echo $id; ?>" placeholder="Comment (optional) — for a Fix: what should it say instead?"></textarea>
      </div>
    <?php endforeach; ?>
  <?php endforeach; ?>

  <h2>Overall Notes (optional)</h2>
  <textarea class="overall-area" name="overall" placeholder="Anything else — tone, strategy, ideas, concerns. You can also just talk to Chris directly; this is the paper trail."></textarea>

  <div class="saved-note" id="saveNote">✓ Progress saved on this device</div>
  <button type="submit">Send Review →</button>
  <p class="small" style="text-align:center;">Your answers save automatically as you go and are delivered two ways (project tracker + server backup). Do not refresh after submitting.</p>
  </form>

  <script>
  (function() {
    var KEY = 'bwm_review_draft_v1';
    var form = document.getElementById('reviewForm');
    if (!form) return;
    var inputs = form.querySelectorAll('input[type=radio], textarea');
    var saveTimer = null;

    function collect() {
      var data = {};
      form.querySelectorAll('input[type=radio]:checked').forEach(function(r) { data[r.name] = r.value; });
      form.querySelectorAll('textarea').forEach(function(t) { if (t.value.trim() !== '') data[t.name] = t.value; });
      return data;
    }
    function save() {
      try { localStorage.setItem(KEY, JSON.stringify(collect())); } catch(e) {}
      var note = document.getElementById('saveNote');
      if (note) { note.classList.add('show'); setTimeout(function(){ note.classList.remove('show'); }, 1500); }
    }
    function restore() {
      var raw = null;
      try { raw = localStorage.getItem(KEY); } catch(e) {}
      if (!raw) return false;
      var data;
      try { data = JSON.parse(raw); } catch(e) { return false; }
      var restored = false;
      Object.keys(data).forEach(function(name) {
        var val = data[name];
        if (typeof val === 'string' && name.indexOf('v_') === 0) {
          var radio = form.querySelector('input[name="' + name + '"][value="' + val + '"]');
          if (radio) { radio.checked = true; restored = true; }
        } else {
          var ta = form.querySelector('textarea[name="' + name + '"]');
          if (ta) { ta.value = val; if (val.trim() !== '') restored = true; }
        }
      });
      return restored;
    }
    if (restore()) {
      var note = document.getElementById('restoreNote');
      if (note) note.style.display = 'block';
    }
    inputs.forEach(function(el) {
      el.addEventListener('change', save);
      if (el.tagName === 'TEXTAREA') {
        el.addEventListener('input', function() { clearTimeout(saveTimer); saveTimer = setTimeout(save, 700); });
      }
    });
  })();
  </script>
<?php endif; ?>

</div>
</body>
</html>