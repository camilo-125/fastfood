import Link from "next/link"
import { Facebook, Instagram, Twitter } from "lucide-react"

export function Footer() {
  return (
    <footer id="contacto" className="border-t border-border bg-muted">
      <div className="container mx-auto px-4 py-12">
        <div className="grid gap-8 md:grid-cols-4">
          <div>
            <div className="mb-4 flex items-center gap-2">
              <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                <span className="font-sans text-2xl font-bold text-primary-foreground">F</span>
              </div>
              <span className="font-sans text-xl font-bold text-foreground">FastBite</span>
            </div>
            <p className="font-sans text-sm leading-relaxed text-muted-foreground">
              La mejor comida rápida de la ciudad, preparada con amor y entregada con rapidez.
            </p>
          </div>

          <div>
            <h4 className="mb-4 font-sans text-sm font-bold text-foreground">Menú</h4>
            <ul className="space-y-2 font-sans text-sm">
              <li>
                <Link href="#" className="text-muted-foreground transition-colors hover:text-foreground">
                  Hamburguesas
                </Link>
              </li>
              <li>
                <Link href="#" className="text-muted-foreground transition-colors hover:text-foreground">
                  Pizzas
                </Link>
              </li>
              <li>
                <Link href="#" className="text-muted-foreground transition-colors hover:text-foreground">
                  Alitas
                </Link>
              </li>
              <li>
                <Link href="#" className="text-muted-foreground transition-colors hover:text-foreground">
                  Bebidas
                </Link>
              </li>
            </ul>
          </div>

          <div>
            <h4 className="mb-4 font-sans text-sm font-bold text-foreground">Empresa</h4>
            <ul className="space-y-2 font-sans text-sm">
              <li>
                <Link href="#" className="text-muted-foreground transition-colors hover:text-foreground">
                  Nosotros
                </Link>
              </li>
              <li>
                <Link href="#" className="text-muted-foreground transition-colors hover:text-foreground">
                  Ubicaciones
                </Link>
              </li>
              <li>
                <Link href="#" className="text-muted-foreground transition-colors hover:text-foreground">
                  Trabaja con nosotros
                </Link>
              </li>
              <li>
                <Link href="#" className="text-muted-foreground transition-colors hover:text-foreground">
                  Contacto
                </Link>
              </li>
            </ul>
          </div>

          <div>
            <h4 className="mb-4 font-sans text-sm font-bold text-foreground">Síguenos</h4>
            <div className="flex gap-4">
              <Link
                href="#"
                className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary transition-colors hover:bg-primary hover:text-primary-foreground"
              >
                <Facebook className="h-5 w-5" />
              </Link>
              <Link
                href="#"
                className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary transition-colors hover:bg-primary hover:text-primary-foreground"
              >
                <Instagram className="h-5 w-5" />
              </Link>
              <Link
                href="#"
                className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary transition-colors hover:bg-primary hover:text-primary-foreground"
              >
                <Twitter className="h-5 w-5" />
              </Link>
            </div>
          </div>
        </div>

        <div className="mt-12 border-t border-border pt-8 text-center font-sans text-sm text-muted-foreground">
          <p>&copy; 2025 FastBite. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  )
}
