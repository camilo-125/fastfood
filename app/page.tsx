import { Header } from "@/components/header"
import { Hero } from "@/components/hero"
import { FeaturedMenu } from "@/components/featured-menu"
import { Promotions } from "@/components/promotions"
import { Features } from "@/components/features"
import { Footer } from "@/components/footer"

export default function Home() {
  return (
    <main className="min-h-screen">
      <Header />
      <Hero />
      <FeaturedMenu />
      <Promotions />
      <Features />
      <Footer />
    </main>
  )
}
