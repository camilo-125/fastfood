import { Button } from "@/components/ui/button"
import { ArrowRight } from "lucide-react"

export function Hero() {
  return (
    <section className="relative overflow-hidden bg-gradient-to-b from-muted to-background py-20 md:py-32">
      <div className="container mx-auto px-4">
        <div className="grid items-center gap-12 lg:grid-cols-2">
          <div className="flex flex-col gap-8">
            <div className="inline-flex items-center gap-2 self-start rounded-full bg-secondary/20 px-4 py-2">
              <span className="h-2 w-2 animate-pulse rounded-full bg-secondary"></span>
              <span className="font-sans text-sm font-medium text-foreground">Entrega en 30 minutos o gratis</span>
            </div>

            <h1 className="font-sans text-5xl font-bold leading-tight text-balance text-foreground md:text-6xl lg:text-7xl">
              Sabor auténtico en cada bocado
            </h1>

            <p className="font-sans text-lg leading-relaxed text-muted-foreground md:text-xl">
              Las mejores hamburguesas, pizzas y alitas de la ciudad. Ingredientes frescos, preparación rápida y sabor
              inolvidable.
            </p>

            <div className="flex flex-col gap-4 sm:flex-row">
              <Button size="lg" className="gap-2 text-base">
                Ver Menú Completo
                <ArrowRight className="h-5 w-5" />
              </Button>
              <Button size="lg" variant="outline" className="text-base bg-transparent">
                Ofertas Especiales
              </Button>
            </div>

            <div className="flex items-center gap-8 pt-4">
              <div>
                <div className="font-sans text-3xl font-bold text-foreground">50K+</div>
                <div className="font-sans text-sm text-muted-foreground">Clientes felices</div>
              </div>
              <div className="h-12 w-px bg-border"></div>
              <div>
                <div className="font-sans text-3xl font-bold text-foreground">4.8★</div>
                <div className="font-sans text-sm text-muted-foreground">Calificación promedio</div>
              </div>
              <div className="h-12 w-px bg-border"></div>
              <div>
                <div className="font-sans text-3xl font-bold text-foreground">30min</div>
                <div className="font-sans text-sm text-muted-foreground">Tiempo de entrega</div>
              </div>
            </div>
          </div>

          <div className="relative">
            <div className="absolute -right-4 -top-4 h-72 w-72 rounded-full bg-primary/20 blur-3xl"></div>
            <div className="absolute -bottom-4 -left-4 h-72 w-72 rounded-full bg-secondary/20 blur-3xl"></div>
            <img
              src="/delicious-gourmet-burger-with-fries-and-drink-on-w.jpg"
              alt="Hamburguesa deliciosa"
              className="relative z-10 h-auto w-full rounded-2xl object-cover shadow-2xl"
            />
          </div>
        </div>
      </div>
    </section>
  )
}
