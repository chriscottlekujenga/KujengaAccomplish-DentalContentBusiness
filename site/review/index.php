<?php
/**
 * Brush With Me — Clinical Review Form (Password-Gated)
 * URL: brushwithme.com/review  (this file = index.php inside /review/)
 * Gate: session-based password check (hash below)
 * Results: emailed to owner + timestamped backup in /review/reviews/
 *
 * SETUP (one-time, after upload):
 *   1. Via cPanel File Manager, create a file hash.php in /review/ with:
 *        <?php echo password_hash('THE_PASSWORD', PASSWORD_DEFAULT);
 *   2. Visit brushwithme.com/review/hash.php once, copy the output.
 *   3. Paste it into PASSWORD_HASH below, delete hash.php. Done.
 */

// ===== CONFIG =====
define('PASSWORD_HASH', 'REPLACE_WITH_GENERATED_HASH'); // setup step above
define('OWNER_EMAIL', 'chris@webkujenga.com'); // TODO: swap to hello@brushwithme.com when brand email exists
define('BRAND', 'Brush With Me');
$BACKUP_DIR = __DIR__ . '/reviews';

session_start();

// ===== GATE =====
$unlocked = isset($_SESSION['review_unlocked']) && $_SESSION['review_unlocked'] === true;
if (!$unlocked && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gate_password'])) {
  if (hash_equals(PASSWORD_HASH, crypt($_POST['gate_password'], PASSWORD_HASH)) || password_verify($_POST['gate_password'], PASSWORD_HASH)) {
    $_SESSION['review_unlocked'] = true;
    $unlocked = true;
  } else {
    $gate_error = 'That password doesn\'t look right — try again.';
  }
}
if ($unlocked && isset($_GET['lock'])) { session_destroy(); $unlocked = false; }

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

  $host = $_SERVER['HTTP_HOST'] ?? 'brushwithme.com';
  $ok1 = @mail(OWNER_EMAIL, '[' . BRAND . '] Clinical review submitted ' . date('Y-m-d'), $body,
      "From: no-reply@$host\r\nContent-Type: text/plain; charset=UTF-8");
  if (!is_dir($BACKUP_DIR)) { @mkdir($BACKUP_DIR, 0755); }
  $ok2 = @file_put_contents($BACKUP_DIR . '/review_' . date('Y-m-d_His') . '.txt', $body);
  $sent = $ok1 || $ok2;
  if (!$sent) { $error = 'Could not save or send — tell Chris; your answers are still on screen (do not refresh).'; }
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
  h2 { color: var(--teal); font-size: 1.05rem; margin: 28px 0 10px; border-bottom: 2px solid var(--mint); padding-bottom: 6px; }
  .item { background: #fff; border: 1px solid #d8e6e2; border-radius: 8px; padding: 12px 14px; margin: 10px 0; }
  .item .label { display: flex; gap: 10px; }
  .item .id { font-weight: 700; color: var(--teal); min-width: 42px; }
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
    Your answers were sent to Chris and saved as a backup.<br>
    Fixes get applied to everything, and your rules get recorded for all future content.<br><br>
    <span class="small">Nothing publishes without your sign-off on that item.</span>
  </div>

<?php else: ?>
  <!-- ============ REVIEW FORM ============ -->
  <h1>🦷 <?php echo BRAND; ?> — Clinical Review</h1>
  <div class="sub">Sprint output, Days 1–10 · 37 items · est. 90–120 min (future weekly packets run 60–90)</div>
  <?php if (!empty($error)): ?><div class="err"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
  <div class="notice">
    <b>How this works:</b> each row = one claim or decision. Mark <b>Approve</b>, <b>Fix</b>, or <b>Skip</b> — add a comment on any Fix (or wherever you have thoughts). The full materials live at
    <b>github.com/chriscottlekujenga/KujengaAccomplish-DentalContentBusiness</b> (posts in <i>content/posts</i>, products in <i>content/products</i>).
    Submit at the bottom — it goes straight to Chris. Works on your phone. <a href="?lock=1">Lock again</a>
  </div>

  <form method="post" action="">
  <?php foreach ($SECTIONS as $section => $items): ?>
    <h2><?php echo htmlspecialchars($section); ?></h2>
    <?php foreach ($items as [$id, $label]): ?>
      <div class="item">
        <div class="label"><span class="id"><?php echo $id; ?></span><span><?php echo htmlspecialchars($label); ?></span></div>
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

  <button type="submit">Send Review to Chris →</button>
  <p class="small" style="text-align:center;">Your answers save even if email fails (backup file on the server). Do not refresh after submitting.</p>
  </form>
<?php endif; ?>

</div>
</body>
</html>