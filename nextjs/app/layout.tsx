import type { Metadata, Viewport } from "next";
import { Barlow_Condensed, DM_Sans } from "next/font/google";
import { site } from "@/content/site";
import Reveal from "@/components/Reveal";
import "./globals.css";

/* next/font self-hosts these at build time: no request to Google, no layout
   shift, and the site keeps working if fonts.googleapis.com is unreachable. */
const display = Barlow_Condensed({
  subsets: ["latin"],
  weight: ["700", "800"],
  style: ["normal", "italic"],
  variable: "--font-display",
  display: "swap",
});
const body = DM_Sans({
  subsets: ["latin"],
  weight: ["400", "500", "700"],
  variable: "--font-body",
  display: "swap",
});

export const metadata: Metadata = {
  metadataBase: new URL(site.domain),
  title: site.title,
  description: site.description,
  alternates: { canonical: "/" },
  openGraph: {
    type: "website",
    siteName: site.name,
    title: site.title,
    description: site.description,
    url: "/",
    images: [{ url: site.ogImage, width: 1200, height: 630,
      alt: "Students wearing VR headsets in a skilled-trades classroom" }],
  },
  twitter: {
    card: "summary_large_image",
    title: site.name,
    description: "Immersive VR lessons that prepare learners for hands-on, instructor-led trade training.",
    images: [site.ogImage],
  },
  icons: {
    icon: "/assets/favicon/favicon.svg",
    apple: "/assets/favicon/apple-touch-icon.png",
  },
};

export const viewport: Viewport = {
  themeColor: "#F5F8FC",
  colorScheme: "light",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en" className={`${display.variable} ${body.variable}`}>
      <head>
        {/* The hero photograph is the largest element above the fold. Without
            this it queues behind five font preloads; with it the browser
            starts fetching it in the first round. */}
        <link
          rel="preload"
          as="image"
          href="/assets/images/hero-vr-1448.webp"
          imageSrcSet="/assets/images/hero-vr-800.webp 800w, /assets/images/hero-vr-1200.webp 1200w, /assets/images/hero-vr-1448.webp 1448w"
          imageSizes="100vw"
          fetchPriority="high"
        />
      </head>
      <body>
        <a className="skip" href="#main">Skip to main content</a>
        {children}
        <Reveal />
      </body>
    </html>
  );
}
