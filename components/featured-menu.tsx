import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Plus } from "lucide-react"

const menuItems = [
  {
    name: "Mega Burger Clásica",
    description: "Doble carne, queso cheddar, lechuga, tomate y nuestra salsa especial",
    price: "$12.99",
    image: "/classic-double-cheeseburger-with-lettuce-and-tomat.jpg",
    popular: true,
  },
  {
    name: "Pizza Suprema",
    description: "Pepperoni, champiñones, pimientos, aceitunas y extra queso",
    price: "$18.99",
    image: "/supreme-pizza-with-pepperoni-mushrooms-and-peppers.jpg",
    popular: true,
  },
  {
    name: "Alitas BBQ",
    description: "12 alitas crujientes con salsa BBQ ahumada y aderezo ranch",
    price: "$14.99",
    image: "/crispy-bbq-chicken-wings-with-ranch-dressing.jpg",
    popular: false,
  },
  {
    name: "Papas Cargadas",
    description: "Papas fritas con queso fundido, tocino y cebollín",
    price: "$8.99",
    image: "/loaded-french-fries-with-cheese-bacon-and-green-on.jpg",
    popular: false,
  },
]

export function FeaturedMenu() {
  return (
    <section id="menu" className="py-20 md:py-32">
      <div className="container mx-auto px-4">
        <div className="mb-12 flex flex-col gap-4 text-center">
          <h2 className="font-sans text-4xl font-bold text-balance text-foreground md:text-5xl">Nuestros favoritos</h2>
          <p className="mx-auto max-w-2xl font-sans text-lg leading-relaxed text-muted-foreground">
            Los platillos más populares que nuestros clientes aman. Preparados frescos todos los días.
          </p>
        </div>

        <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
          {menuItems.map((item) => (
            <Card key={item.name} className="group overflow-hidden transition-all hover:shadow-xl">
              <div className="relative overflow-hidden">
                {item.popular && (
                  <div className="absolute left-4 top-4 z-10 rounded-full bg-secondary px-3 py-1 font-sans text-xs font-bold text-secondary-foreground">
                    Popular
                  </div>
                )}
                <img
                  src={item.image || "/placeholder.svg"}
                  alt={item.name}
                  className="h-64 w-full object-cover transition-transform duration-300 group-hover:scale-110"
                />
              </div>
              <CardContent className="p-6">
                <div className="mb-4 flex items-start justify-between">
                  <div>
                    <h3 className="mb-2 font-sans text-xl font-bold text-card-foreground">{item.name}</h3>
                    <p className="font-sans text-sm leading-relaxed text-muted-foreground">{item.description}</p>
                  </div>
                </div>
                <div className="flex items-center justify-between">
                  <span className="font-sans text-2xl font-bold text-primary">{item.price}</span>
                  <Button size="icon" className="rounded-full">
                    <Plus className="h-5 w-5" />
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>

        <div className="mt-12 text-center">
          <Button size="lg" variant="outline">
            Ver Menú Completo
          </Button>
        </div>
      </div>
    </section>
  )
}
