import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateactiveperiodComponent } from './createactiveperiod.component';

describe('CreateactiveperiodComponent', () => {
  let component: CreateactiveperiodComponent;
  let fixture: ComponentFixture<CreateactiveperiodComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CreateactiveperiodComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CreateactiveperiodComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
