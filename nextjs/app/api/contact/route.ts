import { NextResponse } from "next/server";
import nodemailer from "nodemailer";

export const runtime = "nodejs";

type Body = { name?: string; email?: string; message?: string; company?: string };

export async function POST(req: Request) {
  let body: Body;
  try { body = await req.json(); }
  catch { return NextResponse.json({ ok: false, error: "Invalid request." }, { status: 400 }); }

  // Honeypot: real people leave this hidden field empty. Bots fill it in.
  if (body.company) return NextResponse.json({ ok: true });

  const name = (body.name ?? "").trim();
  const email = (body.email ?? "").trim();
  const message = (body.message ?? "").trim();

  if (!name || !email || !message)
    return NextResponse.json({ ok: false, error: "Please complete every field." }, { status: 400 });
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email))
    return NextResponse.json({ ok: false, error: "That email address does not look right." }, { status: 400 });
  if (message.length > 5000)
    return NextResponse.json({ ok: false, error: "That message is too long." }, { status: 400 });

  const { SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, CONTACT_TO } = process.env;
  if (!SMTP_HOST || !SMTP_USER || !SMTP_PASS || !CONTACT_TO) {
    console.error("[contact] SMTP is not configured; see .env.example");
    return NextResponse.json(
      { ok: false, error: "The contact form is not configured yet." }, { status: 503 });
  }

  try {
    const port = Number(SMTP_PORT ?? 465);
    const transport = nodemailer.createTransport({
      host: SMTP_HOST, port, secure: port === 465,
      auth: { user: SMTP_USER, pass: SMTP_PASS },
    });
    await transport.sendMail({
      from: `"X-VR Skilled Trades" <${SMTP_USER}>`,
      to: CONTACT_TO,
      replyTo: `"${name}" <${email}>`,
      subject: `Enquiry from ${name}`,
      text: `From: ${name} <${email}>\n\n${message}`,
    });
    return NextResponse.json({ ok: true });
  } catch (err) {
    console.error("[contact] send failed", err);
    return NextResponse.json(
      { ok: false, error: "Could not send just now. Please try again shortly." }, { status: 502 });
  }
}
