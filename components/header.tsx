import Link from "next/link"
import { Button } from "@/components/ui/button"
import { Menu, ShoppingCart } from "lucide-react"

export function Header() {
  return (
    <header className="sticky top-0 z-50 w-full border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="container mx-auto flex h-16 items-center justify-between px-4">
        <div className="flex items-center gap-8">
          <Link href="/" className="flex items-center gap-2">
            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
              <span className="font-sans text-2xl font-bold text-primary-foreground">F</span>
            </div>
            <span className="font-sans text-xl font-bold text-foreground">FastBite</span>
          </Link>

          <nav className="hidden items-center gap-6 md:flex">
            <Link
              href="#menu"
              className="font-sans text-sm font-medium text-foreground transition-colors hover:text-primary"
            >
              Menú
            </Link>
            <Link
              href="#promociones"
              className="font-sans text-sm font-medium text-foreground transition-colors hover:text-primary"
            >
              Promociones
            </Link>
            <Link
              href="#nosotros"
              className="font-sans text-sm font-medium text-foreground transition-colors hover:text-primary"
            >
              Nosotros
            </Link>
            <Link
              href="#contacto"
              className="font-sans text-sm font-medium text-foreground transition-colors hover:text-primary"
            >
              Contacto
            </Link>
          </nav>
        </div>

        <div className="flex items-center gap-4">
          <Button variant="ghost" size="icon" className="relative">
            <ShoppingCart className="h-5 w-5" />
            <span className="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-xs font-bold text-primary-foreground">
              0
            </span>
          </Button>
          <Button className="hidden md:inline-flex">Ordenar Ahora</Button>
          <Button variant="ghost" size="icon" className="md:hidden">
            <Menu className="h-5 w-5" />
          </Button>
        </div>
      </div>
    </header>
  )
}
